# 📱 Panduan Setup WhatsApp Notification untuk SIPZIS

## 🎯 Overview

Panduan ini akan membantu Anda setup WhatsApp notification menggunakan **Fonnte.com** sebagai WhatsApp Gateway.

---

## 📋 Langkah-Langkah Setup

### 1. Registrasi di Fonnte.com

1. Buka https://fonnte.com
2. Klik **Daftar** atau **Sign Up**
3. Isi data:
   - Email
   - Password
   - Nomor WhatsApp (akan digunakan sebagai sender)
4. Verifikasi email Anda
5. Login ke dashboard Fonnte

### 2. Dapatkan API Token

1. Login ke dashboard Fonnte
2. Buka menu **Device** → **Add Device**
3. Scan QR Code dengan WhatsApp Anda (WhatsApp yang akan jadi pengirim pesan)
4. Setelah berhasil connect, buka menu **Account** → **API**
5. Copy **API Token** Anda
   - Contoh format: `xxxx@xxxxxxx`

### 3. Konfigurasi di Laravel (.env)

Buka file `.env` di root project Anda dan tambahkan:

```env
# WhatsApp Configuration (Fonnte)
WHATSAPP_API_URL=https://api.fonnte.com/send
WHATSAPP_API_TOKEN=your_fonnte_token_here
WHATSAPP_ENABLED=true
```

**Ganti `your_fonnte_token_here`** dengan token yang Anda copy dari Fonnte!

**Contoh:**

```env
WHATSAPP_API_TOKEN=xxxx@xxxxxxx
WHATSAPP_ENABLED=true
```

### 4. Test Konfigurasi

Buat route test di `routes/web.php`:

```php
use App\Services\WhatsAppService;

Route::get('/test-whatsapp', function() {
    $whatsappService = new WhatsAppService();

    // Ganti dengan nomor HP Anda untuk test
    $testPhone = '628123456789'; // Format: 62xxx

    $result = $whatsappService->testConnection($testPhone);

    return response()->json([
        'success' => $result['success'],
        'message' => $result['message'],
        'response' => $result['response'] ?? null
    ]);
});
```

Akses di browser: `http://localhost/test-whatsapp`

Jika berhasil, Anda akan menerima pesan WhatsApp!

---

## 🔧 Troubleshooting

### ❌ Error: "Token not configured"

**Solusi:**

- Pastikan `.env` sudah ada `WHATSAPP_API_TOKEN`
- Run `php artisan config:clear`
- Restart server: `php artisan serve`

### ❌ Error: "WhatsApp is disabled"

**Solusi:**

- Set `WHATSAPP_ENABLED=true` di `.env`
- Run `php artisan config:clear`

### ❌ Error: "Invalid phone number format"

**Solusi:**

- Nomor HP harus format `62xxx` (tanpa +)
- Contoh benar: `628123456789`
- Contoh salah: `+628123456789`, `08123456789`

### ❌ Pesan tidak terkirim

**Checklist:**

1. ✅ WhatsApp device masih connected di Fonnte dashboard?
2. ✅ Token sudah benar?
3. ✅ Nomor penerima aktif?
4. ✅ Quota Fonnte masih ada?
5. ✅ Cek log di `storage/logs/whatsapp.log`

---

## 💰 Pricing Fonnte (Referensi)

### Free Plan

- ✅ 100 pesan/bulan
- ✅ 1 device
- ✅ Basic features

### Paid Plans

- **Starter**: Rp 50.000/bulan - 500 pesan
- **Basic**: Rp 100.000/bulan - 1.500 pesan
- **Pro**: Rp 200.000/bulan - 5.000 pesan

_Harga bisa berubah, cek: https://fonnte.com/pricing_

---

## 📊 Monitoring & Logs

### Cek Log WhatsApp

```bash
# Windows
type storage\logs\whatsapp.log

# Linux/Mac
tail -f storage/logs/whatsapp.log
```

### Log Format

```
[2025-10-29 10:15:30] whatsapp.INFO: WhatsApp message sent
{
  "phone": "628123456789",
  "status": 200,
  "response": {"status": true},
  "sent_at": "2025-10-29 10:15:30"
}
```

---

## 🎨 Customize Template Pesan

Edit file `app/Services/WhatsAppService.php` untuk mengubah template pesan.

**Contoh:**

```php
public function sendPaymentSuccess(ZakatPayment $payment, $phone)
{
    $message = "✅ *DONASI BERHASIL*\n\n";
    $message .= "Halo *{$payment->muzakki->name}*,\n\n";
    // ... customize sesuai kebutuhan

    return $this->sendMessage($phone, $message);
}
```

---

## 🚀 Production Checklist

Sebelum deploy ke production:

- [ ] Test kirim pesan ke nomor real
- [ ] Setup monitoring di Fonnte dashboard
- [ ] Set `WHATSAPP_ENABLED=true`
- [ ] Backup WhatsApp chat history (jaga-jaga device bermasalah)
- [ ] Setup alert jika quota hampir habis
- [ ] Dokumentasikan token ke tim (secure!)

---

## 🔐 Security Best Practices

1. **Jangan commit `.env` ke Git**

   ```bash
   # Pastikan .env ada di .gitignore
   echo ".env" >> .gitignore
   ```

2. **Simpan token di secure location**
   - Gunakan password manager untuk tim
   - Jangan share token via chat/email

3. **Rotate token secara berkala**
   - Generate token baru setiap 3-6 bulan
   - Update di production server

4. **Monitor usage**
   - Cek dashboard Fonnte setiap minggu
   - Set alert jika ada unusual activity

---

## 📱 Format Nomor HP

### ✅ Format Benar

- `628123456789`
- `6281234567890`
- `62812345678`

### ❌ Format Salah

- `+628123456789` (ada + sign)
- `08123456789` (dimulai 0, bukan 62)
- `8123456789` (kurang country code)
- `62-812-3456-789` (ada dash/symbol)

### Auto-format di Form

Form donasi sudah otomatis format nomor HP:

- Input: `08123456789` → Auto convert: `628123456789`
- Input: `8123456789` → Auto tambah: `628123456789`

---

## 🆚 Alternatif WhatsApp Gateway

Jika Fonnte tidak cocok, alternatif lain:

### 1. **Twilio WhatsApp Business API**

- **Pro:** Enterprise-grade, reliable
- **Con:** Mahal ($0.005/pesan), perlu approval
- **Website:** https://www.twilio.com/whatsapp

### 2. **WABLAS**

- **Pro:** Support Indonesia, mudah
- **Con:** Perlu hosting sendiri
- **Website:** https://wablas.com

### 3. **WhatsApp Business API (Official)**

- **Pro:** Official dari Meta, unlimited
- **Con:** Butuh approval, setup kompleks
- **Website:** https://business.whatsapp.com

---

## 📞 Support

### Fonnte Support

- Email: support@fonnte.com
- Website: https://fonnte.com
- Dokumentasi API: https://fonnte.com/api

### SIPZIS Development Team

- Email: dev@sipzis.com
- Issue tracker: [GitHub Issues]

---

## 📚 Referensi

- [Fonnte API Documentation](https://fonnte.com/api)
- [WhatsApp Business Policy](https://www.whatsapp.com/legal/business-policy)
- [Laravel HTTP Client](https://laravel.com/docs/http-client)

---

**Last Updated:** 2025-10-29  
**Version:** 1.0.0  
**Maintainer:** SIPZIS Development Team
