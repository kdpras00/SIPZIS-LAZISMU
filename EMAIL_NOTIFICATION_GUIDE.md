# 📧 Email Notification System - User Guide

## Deskripsi
Sistem notifikasi email otomatis untuk muzakki yang terintegrasi dengan Gmail SMTP. Email akan dikirim otomatis pada event-event tertentu.

## 🎯 Kapan Email Dikirim?

### 1. **Welcome Email** - Saat Registrasi
**Trigger:** Muzakki baru mendaftar/registrasi

**Isi Email:**
- Ucapan selamat datang
- Informasi singkat tentang SIPZIS
- Motivasi untuk berdonasi

**Template:** `resources/views/emails/welcome.blade.php`

**Contoh:**
```
Subject: Selamat Datang di Sistem Informasi Pengelolaan Zakat SIPZIS

Halo, Ahmad! 👋
Selamat datang di SIPZIS!
Terima kasih telah bergabung bersama kami 🙏
```

### 2. **Payment Notification** - Saat Pembayaran
**Trigger:** Status pembayaran berubah (created/updated)

#### 2.1 Pembayaran Pending
**Status:** `pending`
**Isi:**
- Informasi pembayaran menunggu konfirmasi
- Detail transaksi
- Instruksi untuk upload bukti transfer

**Template:** `resources/views/emails/donor/payment-status.blade.php`

#### 2.2 Pembayaran Berhasil  
**Status:** `completed`
**Isi:**
- Konfirmasi pembayaran berhasil
- Detail donasi lengkap
- Ucapan terima kasih
- **BONUS:** Kirim 2 email:
  1. Payment Status (berhasil)
  2. Payment Confirmation (tanda terima)

**Template:** 
- `resources/views/emails/donor/payment-status.blade.php`
- `resources/views/emails/donor/payment-confirmation.blade.php`

#### 2.3 Pembayaran Gagal
**Status:** `failed`
**Isi:**
- Informasi pembayaran gagal
- Saran untuk mencoba lagi
- Kontak bantuan

#### 2.4 Pembayaran Dibatalkan
**Status:** `cancelled`
**Isi:**
- Informasi pembayaran dibatalkan
- Opsi untuk membuat pembayaran baru

## 🔧 Konfigurasi Email

### Setup Gmail SMTP
File: `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="SIPZIS - Sistem Zakat"
```

### Generate App Password (Gmail)
1. Buka [Google Account Security](https://myaccount.google.com/security)
2. Aktifkan **2-Step Verification**
3. Generate **App Password**
4. Copy password ke `.env` (MAIL_PASSWORD)

## 📝 Implementasi Teknis

### 1. AuthController - Welcome Email
**File:** `app/Http/Controllers/Auth/AuthController.php`

```php
// Send welcome email to new muzakki
try {
    \Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
} catch (\Exception $e) {
    \Log::error('Failed to send welcome email: ' . $e->getMessage());
}
```

**Event:** User register
**Method:** `register()`
**Line:** ~190

### 2. ZakatPaymentObserver - Payment Email
**File:** `app/Observers/ZakatPaymentObserver.php`

```php
// Send email notification to muzakki about payment status
$this->sendPaymentEmail($zakatPayment);
```

**Events:** 
- `created()` - Pembayaran baru dibuat
- `updated()` - Status pembayaran berubah

**Method:** `sendPaymentEmail()`
**Line:** ~136-181

## 🧪 Testing Email

### Test Welcome Email
```bash
# Via route test
GET /test-welcome-email
```

Atau manual via tinker:
```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
Mail::to($user->email)->send(new App\Mail\WelcomeMail($user));
// Check inbox/spam
```

### Test Payment Email
```bash
php artisan tinker
```

```php
// Test pending payment
$payment = App\Models\ZakatPayment::first();
Mail::to($payment->muzakki->email)
    ->send(new App\Mail\DonorPaymentStatus($payment, 'pending'));

// Test completed payment
Mail::to($payment->muzakki->email)
    ->send(new App\Mail\DonorPaymentStatus($payment, 'completed'));

// Test payment confirmation
Mail::to($payment->muzakki->email)
    ->send(new App\Mail\DonorPaymentConfirmation($payment));
```

## 📊 Flow Diagram

### Registration Flow
```
User Register
    ↓
Create User & Muzakki
    ↓
Observer: MuzakkiObserver (campaign_url auto-generated)
    ↓
Send Welcome Email ✉️
    ↓
Redirect to Login Page
```

### Payment Flow
```
User Make Payment
    ↓
ZakatPayment Created (status: pending)
    ↓
Observer: ZakatPaymentObserver
    ↓
Send Pending Email ✉️
    ↓
Admin Verify Payment
    ↓
ZakatPayment Updated (status: completed)
    ↓
Observer: ZakatPaymentObserver
    ↓
Send 2 Emails ✉️✉️
  - Payment Status (completed)
  - Payment Confirmation
```

## 📧 Email Templates

### Available Templates
1. **WelcomeMail** - `app/Mail/WelcomeMail.php`
2. **DonorPaymentStatus** - `app/Mail/DonorPaymentStatus.php`
3. **DonorPaymentConfirmation** - `app/Mail/DonorPaymentConfirmation.php`
4. **DonorReceipt** - `app/Mail/DonorReceipt.php` (dengan PDF attachment)
5. **DonorZakatReminder** - `app/Mail/DonorZakatReminder.php`

### View Templates
Located in: `resources/views/emails/`

```
emails/
├── welcome.blade.php
└── donor/
    ├── payment-status.blade.php
    ├── payment-confirmation.blade.php
    ├── receipt.blade.php
    └── zakat-reminder.blade.php
```

## 🔍 Logging & Debugging

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

### Success Log Example
```
[2024-10-29 12:34:56] local.INFO: Payment email sent to: user@example.com for payment: ZKT-2024-00001 with status: completed
```

### Error Log Example
```
[2024-10-29 12:35:01] local.ERROR: Failed to send payment email: Connection timeout for payment: ZKT-2024-00002
```

## 🚨 Troubleshooting

### Email tidak terkirim?

#### 1. Cek Konfigurasi
```bash
php artisan config:clear
php artisan cache:clear
```

Verify `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # Bukan password biasa!
MAIL_ENCRYPTION=tls
```

#### 2. Test Connection
```bash
php artisan tinker
```

```php
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

#### 3. Cek Gmail Settings
- ✅ 2-Step Verification aktif?
- ✅ App Password sudah dibuat?
- ✅ Less secure apps disabled (gunakan App Password)

#### 4. Cek Spam Folder
Email mungkin masuk ke spam folder penerima

#### 5. Firewall/Port Blocked
Pastikan port 587 tidak diblock oleh firewall

### Email delay?

Email queue akan diproses secara asynchronous jika menggunakan queue driver. Untuk langsung kirim:

```env
QUEUE_CONNECTION=sync
```

## 📈 Best Practices

### 1. **Always Use Try-Catch**
```php
try {
    Mail::to($email)->send(new SomeMail($data));
} catch (\Exception $e) {
    \Log::error('Failed to send email: ' . $e->getMessage());
}
```

### 2. **Log Everything**
```php
\Log::info('Email sent to: ' . $email);
\Log::error('Email failed: ' . $e->getMessage());
```

### 3. **Don't Block Process**
Jika email gagal, jangan stop proses utama (registrasi/payment)

```php
// ❌ BAD
Mail::to($email)->send(new SomeMail());

// ✅ GOOD
try {
    Mail::to($email)->send(new SomeMail());
} catch (\Exception $e) {
    // Log but continue
    \Log::error('Email failed: ' . $e->getMessage());
}
```

### 4. **Use Queue for Production**
```env
QUEUE_CONNECTION=database
```

```bash
php artisan queue:work
```

### 5. **Validate Email Before Send**
```php
if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    Mail::to($user->email)->send(new SomeMail($user));
}
```

## 🎨 Customize Email Templates

### Ubah Design
Edit file di `resources/views/emails/`

### Ubah Subject/Content
Edit file di `app/Mail/`

Contoh:
```php
public function build()
{
    return $this->subject('Custom Subject')
        ->view('emails.custom-template');
}
```

## 📦 Email Features

### Current Features
- ✅ Welcome email saat registrasi
- ✅ Payment notification (pending/completed/failed/cancelled)
- ✅ Payment confirmation
- ✅ Automatic retry dengan try-catch
- ✅ Logging untuk debugging
- ✅ Responsive email templates
- ✅ Multiple emails untuk completed payment

### Future Features (Optional)
- ⏳ Email dengan PDF attachment (receipt)
- ⏳ Monthly zakat reminder
- ⏳ Campaign progress notification
- ⏳ Distribution report email
- ⏳ Newsletter blast

## 🔐 Security Notes

1. **Never commit `.env`** - Contains sensitive passwords
2. **Use App Password** - Not your regular Gmail password
3. **Enable 2FA** - Required for App Password
4. **Rate Limiting** - Gmail has sending limits (500/day for free accounts)
5. **Validate Recipients** - Always validate email format

## 📞 Support

### Check Status
```bash
# Clear cache
php artisan optimize:clear

# Check mail config
php artisan config:show mail

# View logs
tail -f storage/logs/laravel.log
```

### Common Issues
1. **"Connection refused"** → Check MAIL_HOST and MAIL_PORT
2. **"Authentication failed"** → Check App Password
3. **"SSL certificate problem"** → Check MAIL_ENCRYPTION
4. **Email di spam** → Add SPF/DKIM records

## 📝 Summary

### Email Triggers
| Event | Email | Recipient | Template |
|-------|-------|-----------|----------|
| User Register | Welcome Email | Muzakki | `welcome.blade.php` |
| Payment Pending | Pending Notification | Muzakki | `payment-status.blade.php` |
| Payment Completed | Success + Confirmation | Muzakki | `payment-status.blade.php` + `payment-confirmation.blade.php` |
| Payment Failed | Failed Notification | Muzakki | `payment-status.blade.php` |
| Payment Cancelled | Cancelled Notification | Muzakki | `payment-status.blade.php` |

### Files Modified
1. ✅ `app/Http/Controllers/Auth/AuthController.php` - Send welcome email
2. ✅ `app/Observers/ZakatPaymentObserver.php` - Send payment emails
3. ✅ Email templates already exist in `resources/views/emails/`

### Status
- ✅ **IMPLEMENTED & READY**
- ✅ Gmail SMTP configured
- ✅ Welcome email on registration
- ✅ Payment notification on all status changes
- ✅ Error handling & logging
- ✅ Beautiful responsive email templates

---

**Last Updated:** 29 Oktober 2024  
**Status:** ✅ **PRODUCTION READY**

