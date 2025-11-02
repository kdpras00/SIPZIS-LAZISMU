# Campaign URL Auto-Generation

## Deskripsi
Sistem ini otomatis menghasilkan `campaign_url` untuk setiap muzakki berdasarkan email mereka. Campaign URL digunakan untuk halaman campaign personal dari setiap muzakki.

## Format Campaign URL
```
https://domain.com/campaigner/{email}
```

Contoh:
- Email: `john@example.com`
- Campaign URL: `https://sistemzakat.com/campaigner/john@example.com`

## Implementasi

### 1. Model Observer (Auto-Generation)
File: `app/Observers/MuzakkiObserver.php`

Observer ini otomatis di-trigger saat:
- **Membuat muzakki baru** (`creating` event): Otomatis generate campaign_url jika email ada
- **Update muzakki** (`updating` event): Update campaign_url jika email berubah atau campaign_url kosong

**Keuntungan:**
- ✅ Tidak perlu manual set campaign_url di controller
- ✅ Konsisten di semua titik pembuatan muzakki
- ✅ Auto-update jika email berubah

### 2. Controller Implementation
File: `app/Http/Controllers/MuzakkiController.php`

#### Create Muzakki (Admin)
```php
// Line 132-133
$campaignUrl = $request->email ? url('/campaigner/' . $request->email) : null;
// Otomatis di-set saat create
```

#### Update Muzakki
Observer akan handle auto-generation, tidak perlu manual set di controller.

### 3. Auth Controller
File: `app/Http/Controllers/Auth/AuthController.php`

Campaign URL otomatis di-generate untuk:
- **User Registration** (line 168-185)
- **Firebase Login** (line 246-256)
- **Normal Login** (line 63-69): Generate jika belum ada

## Command untuk User Lama

### Generate Campaign URLs
Untuk mengisi campaign_url pada muzakki yang sudah ada (user lama):

```bash
# Generate hanya untuk muzakki yang belum punya campaign_url
php artisan muzakki:generate-campaign-urls

# Force regenerate untuk SEMUA muzakki (overwrite existing)
php artisan muzakki:generate-campaign-urls --force
```

**Output:**
```
Starting campaign URL generation...
Normal mode: Generating campaign URLs only for muzakki without one...
Found 2 muzakki to process.
 2/2 [============================] 100%

Campaign URL Generation Complete!
+-----------------+-------+
| Status          | Count |
+-----------------+-------+
| Updated         | 2     |
| Skipped/Failed  | 0     |
| Total Processed | 2     |
+-----------------+-------+
```

### Verbose Mode
Untuk melihat detail setiap update:

```bash
php artisan muzakki:generate-campaign-urls -v
```

Output akan menampilkan:
- Nama muzakki
- Email
- URL lama (jika ada)
- URL baru

## Testing

### 1. Test Create User Baru
```php
// Via Registration
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('password'),
    'role' => 'muzakki',
]);

$muzakki = Muzakki::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'user_id' => $user->id,
]);

// Expected: campaign_url = "http://localhost/campaigner/test@example.com"
```

### 2. Test Update Email
```php
$muzakki = Muzakki::first();
$muzakki->email = 'newemail@example.com';
$muzakki->save();

// Expected: campaign_url otomatis berubah ke "http://localhost/campaigner/newemail@example.com"
```

### 3. Test User Lama
```php
// Cek muzakki tanpa campaign_url
$muzakkiWithoutUrl = Muzakki::whereNull('campaign_url')
    ->orWhere('campaign_url', '')
    ->count();

// Jalankan command
php artisan muzakki:generate-campaign-urls

// Verify: semua muzakki sekarang punya campaign_url
$muzakkiWithoutUrl = Muzakki::whereNull('campaign_url')
    ->orWhere('campaign_url', '')
    ->count();
// Expected: 0
```

## Troubleshooting

### Campaign URL tidak ter-generate
**Solusi:**
1. Pastikan observer terdaftar di `AppServiceProvider.php`
2. Jalankan command manual: `php artisan muzakki:generate-campaign-urls`
3. Clear cache: `php artisan optimize:clear`

### Campaign URL tidak update saat email berubah
**Cek:**
1. Observer sudah terdaftar?
2. Email benar-benar berubah? (gunakan `$muzakki->isDirty('email')`)
3. Cache tidak mengganggu?

### Command tidak muncul
**Solusi:**
```bash
# Rebuild autoload
composer dump-autoload

# Clear cache
php artisan optimize:clear
php artisan config:clear

# Verify command terdaftar
php artisan list | grep muzakki
```

## Cara Kerja Observer

### Creating Event (Sebelum Save Baru)
```php
public function creating(Muzakki $muzakki): void
{
    if ($muzakki->email && empty($muzakki->campaign_url)) {
        $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
    }
}
```

### Updating Event (Sebelum Update)
```php
public function updating(Muzakki $muzakki): void
{
    // Update jika email berubah
    if ($muzakki->isDirty('email') && $muzakki->email) {
        $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
    }
    
    // Generate jika masih kosong
    if (empty($muzakki->campaign_url) && $muzakki->email) {
        $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
    }
}
```

## Migration untuk User Lama

Jika Anda deploy ke production dengan user lama, jalankan:

```bash
# 1. Backup database terlebih dahulu
mysqldump -u root -p sistemzakat2 > backup_before_campaign_url.sql

# 2. Generate campaign URLs
php artisan muzakki:generate-campaign-urls

# 3. Verify hasilnya
php artisan muzakki:generate-campaign-urls
# Expected: "No muzakki found that need campaign URL generation."
```

## Best Practices

1. **Jangan manual set campaign_url** - Biarkan observer handle otomatis
2. **Email harus unique** - Campaign URL bergantung pada email
3. **Run command setelah migration** - Untuk mengisi data lama
4. **Use verbose mode** untuk troubleshooting - `php artisan muzakki:generate-campaign-urls -v`

## Lokasi File

```
app/
├── Observers/
│   └── MuzakkiObserver.php          # Observer untuk auto-generate
├── Console/Commands/
│   └── GenerateMuzakkiCampaignUrls.php  # Command untuk user lama
├── Providers/
│   └── AppServiceProvider.php       # Register observer
└── Http/Controllers/
    ├── MuzakkiController.php        # Controller muzakki
    └── Auth/AuthController.php      # Auth controller
```

## Changelog

### Version 1.0.0 (29 Oktober 2024)
- ✅ Implementasi MuzakkiObserver untuk auto-generate campaign_url
- ✅ Command untuk generate campaign_url user lama
- ✅ Update semua controller untuk support auto-generation
- ✅ Remove manual generation dari frontend
- ✅ Dokumentasi lengkap

## Support

Jika ada pertanyaan atau issue:
1. Cek log Laravel: `storage/logs/laravel.log`
2. Run command dengan verbose: `php artisan muzakki:generate-campaign-urls -v`
3. Test dengan tinker: `php artisan tinker`

