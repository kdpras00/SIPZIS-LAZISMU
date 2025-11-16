# Cara Kerja Notifikasi Pengingat dan Pesan

## 📋 Status Saat Ini

### ⚠️ **Notifikasi Pengingat (Reminder)**
- **Status:** Method sudah ada, **belum dipanggil otomatis**
- **Method:** `Notification::createReminderNotification($muzakki, $reminderType)`
- **Jenis Reminder:**
  - `zakat`: "Sudah waktunya membayar zakat penghasilan bulan ini."
  - `balance`: "Saldo zakat Anda tersisa Rp200.000, ingin disalurkan?"

### ⚠️ **Notifikasi Pesan (Message)**
- **Status:** Method sudah ada, **belum dipanggil otomatis**
- **Method:** `Notification::createMessageNotification($user, $message, $sender = 'Admin')`
- **Format Pesan:** "[Sender]: [Pesan]"

---

## 🔧 Cara Menggunakan

### **1. Notifikasi Pengingat (Reminder)**

#### **A. Manual - Untuk Muzakki Tertentu**
```php
use App\Models\Muzakki;
use App\Models\Notification;

// Kirim reminder zakat ke muzakki tertentu
$muzakki = Muzakki::find(1);
Notification::createReminderNotification($muzakki, 'zakat');

// Kirim reminder saldo ke muzakki tertentu
Notification::createReminderNotification($muzakki, 'balance');
```

#### **B. Untuk Semua Muzakki**
```php
use App\Models\Muzakki;
use App\Models\Notification;

// Kirim reminder zakat ke semua muzakki
$muzakkiList = Muzakki::whereNotNull('user_id')->get();
foreach ($muzakkiList as $muzakki) {
    Notification::createReminderNotification($muzakki, 'zakat');
}
```

#### **C. Berdasarkan Kondisi (Contoh: Saldo Tersisa)**
```php
use App\Models\Muzakki;
use App\Models\Notification;
use App\Models\ZakatPayment;
use App\Models\ZakatDistribution;

// Kirim reminder saldo ke muzakki yang memiliki saldo tersisa
$muzakkiList = Muzakki::whereNotNull('user_id')->get();
foreach ($muzakkiList as $muzakki) {
    $paid = ZakatPayment::where('muzakki_id', $muzakki->id)
        ->where('status', 'completed')
        ->sum('paid_amount');
    
    $distributed = ZakatDistribution::where('distribution_type', 'cash')
        ->sum('amount'); // Total distribusi (bukan per muzakki)
    
    // Hitung saldo per muzakki (perlu logika lebih kompleks)
    // Untuk sementara, kirim ke semua yang pernah bayar
    if ($paid > 0) {
        Notification::createReminderNotification($muzakki, 'balance');
    }
}
```

---

### **2. Notifikasi Pesan (Message)**

#### **A. Manual - Untuk User Tertentu**
```php
use App\Models\User;
use App\Models\Notification;

// Kirim pesan ke user tertentu
$user = User::find(1);
Notification::createMessageNotification($user, 'Terima kasih atas donasi Anda!', 'Admin');

// Kirim pesan dengan sender custom
Notification::createMessageNotification($user, 'Program baru telah dibuka', 'Tim Program');
```

#### **B. Untuk Semua Muzakki (Broadcast)**
```php
use App\Models\Muzakki;
use App\Models\Notification;

// Kirim pesan broadcast ke semua muzakki
$muzakkiList = Muzakki::whereNotNull('user_id')->get();
foreach ($muzakkiList as $muzakki) {
    if ($muzakki->user) {
        Notification::createMessageNotification(
            $muzakki->user, 
            'Pengumuman penting: Program Ramadhan telah dibuka!', 
            'Admin'
        );
    }
}
```

#### **C. Untuk Muzakki dengan Kondisi Tertentu**
```php
use App\Models\Muzakki;
use App\Models\Notification;

// Kirim pesan ke muzakki yang belum pernah bayar
$muzakkiList = Muzakki::whereNotNull('user_id')
    ->whereDoesntHave('zakatPayments')
    ->get();

foreach ($muzakkiList as $muzakki) {
    if ($muzakki->user) {
        Notification::createMessageNotification(
            $muzakki->user,
            'Yuk mulai berdonasi untuk membantu sesama!',
            'Admin'
        );
    }
}
```

---

## 🚀 Implementasi Otomatis (Rekomendasi)

### **1. Scheduled Job untuk Reminder Zakat Bulanan**

Buat command baru:
```bash
php artisan make:command SendZakatReminders
```

Kemudian di `app/Console/Kernel.php`, tambahkan:
```php
$schedule->command('reminders:zakat-monthly')
    ->monthlyOn(1, '09:00') // Setiap tanggal 1, jam 9 pagi
    ->withoutOverlapping();
```

### **2. Scheduled Job untuk Reminder Saldo**

Buat command untuk cek saldo dan kirim reminder:
```bash
php artisan make:command CheckZakatBalance
```

### **3. Fitur Admin untuk Mengirim Pesan**

Buat controller untuk admin mengirim pesan:
```bash
php artisan make:controller Admin/MessageController
```

---

## 📝 Flow Lengkap

### **Reminder - Manual**
1. Admin/System memanggil method `createReminderNotification()`
2. Notifikasi dibuat dengan `muzakki_id` yang benar
3. Notifikasi masuk ke popup muzzaki
4. Muzzaki melihat notifikasi di popup

### **Reminder - Otomatis (Scheduled Job)**
1. Scheduled job berjalan (misalnya setiap bulan)
2. Sistem mencari muzakki yang memenuhi kondisi
3. Untuk setiap muzakki, panggil `createReminderNotification()`
4. Notifikasi dibuat dan masuk ke popup muzzaki

### **Message - Manual**
1. Admin memanggil method `createMessageNotification()`
2. Notifikasi dibuat dengan `muzakki_id` yang benar
3. Notifikasi masuk ke popup muzzaki
4. Muzzaki melihat pesan di popup

### **Message - Broadcast**
1. Admin memilih "Kirim ke Semua Muzzaki"
2. Sistem loop semua muzakki
3. Untuk setiap muzakki, panggil `createMessageNotification()`
4. Semua muzakki mendapat notifikasi pesan

---

## ✅ Yang Sudah Bekerja

- ✅ Method `createReminderNotification()` sudah ada dan siap digunakan
- ✅ Method `createMessageNotification()` sudah ada dan siap digunakan
- ✅ Notifikasi akan masuk ke muzzaki dengan `muzakki_id` yang benar
- ✅ Notifikasi akan muncul di popup muzzaki

## ⚠️ Yang Perlu Diimplementasikan

- ⚠️ Scheduled job untuk reminder otomatis (opsional)
- ⚠️ Fitur admin untuk mengirim pesan (opsional)
- ⚠️ UI untuk admin mengirim pesan/broadcast (opsional)

---

## 💡 Kesimpulan

**Cara kerja saat ini:**
- Method sudah tersedia dan siap digunakan
- Bisa dipanggil manual dari controller, command, atau scheduled job
- Notifikasi akan langsung masuk ke muzzaki (baik login Google maupun biasa)

**Untuk otomatisasi:**
- Bisa dibuat scheduled job untuk reminder bulanan
- Bisa dibuat fitur admin untuk mengirim pesan
- Semua akan masuk ke muzzaki dengan benar

