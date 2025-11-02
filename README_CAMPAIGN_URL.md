# Campaign URL - Quick Reference

## 🎯 Apa itu Campaign URL?

URL unik untuk setiap muzakki yang digunakan untuk halaman campaign personal mereka.

**Format:** `https://domain.com/campaigner/{email}`

## 🚀 Quick Start

### Untuk User Baru

✅ **Otomatis dibuat!** Tidak perlu action apapun.

### Untuk User Lama

```bash
php artisan muzakki:generate-campaign-urls
```

## 📋 Command Reference

```bash
# Generate untuk muzakki tanpa campaign_url
php artisan muzakki:generate-campaign-urls

# Force regenerate untuk SEMUA muzakki
php artisan muzakki:generate-campaign-urls --force

# Lihat detail setiap update
php artisan muzakki:generate-campaign-urls -v
```

## 🔍 Cek Status

### Via Tinker

```bash
php artisan tinker
```

```php
// Cek total muzakki
App\Models\Muzakki::count()

// Cek muzakki tanpa campaign_url
App\Models\Muzakki::whereNull('campaign_url')->count()

// Lihat contoh campaign_url
App\Models\Muzakki::first()->campaign_url

// Cek campaign_url untuk email tertentu
App\Models\Muzakki::where('email', 'user@example.com')->first()->campaign_url
```

## 🛠️ Troubleshooting

### Campaign URL tidak ter-generate?

```bash
# 1. Clear cache
php artisan optimize:clear

# 2. Run command manual
php artisan muzakki:generate-campaign-urls --force

# 3. Check log
tail -f storage/logs/laravel.log
```

### Observer tidak jalan?

Cek di `app/Providers/AppServiceProvider.php`:

```php
Muzakki::observe(MuzakkiObserver::class); // ← Ini ada?
```

## 📝 Contoh Penggunaan

### Create Muzakki Baru

```php
$muzakki = Muzakki::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'gender' => 'male',
]);

// Campaign URL otomatis: http://domain.com/campaigner/john@example.com
echo $muzakki->campaign_url;
```

### Update Email

```php
$muzakki = Muzakki::find(1);
$muzakki->email = 'newemail@example.com';
$muzakki->save();

// Campaign URL otomatis update: http://domain.com/campaigner/newemail@example.com
echo $muzakki->campaign_url;
```

## 📚 Dokumentasi Lengkap

Lihat `CAMPAIGN_URL_AUTO_GENERATION.md` untuk dokumentasi lengkap.

## ✅ Status

- ✅ Auto-generation aktif
- ✅ Update otomatis saat email berubah
- ✅ Command tersedia untuk user lama
- ✅ Tested dan verified

## 🔗 Related Files

- `app/Observers/MuzakkiObserver.php` - Auto-generation logic
- `app/Console/Commands/GenerateMuzakkiCampaignUrls.php` - Migration command
- `app/Providers/AppServiceProvider.php` - Observer registration

## 💡 Tips

1. Jalankan command setelah deploy pertama kali
2. Campaign URL otomatis untuk semua user baru
3. Tidak perlu kode manual di controller
4. Email harus unique untuk URL unique
