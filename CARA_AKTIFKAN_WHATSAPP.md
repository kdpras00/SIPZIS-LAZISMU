# 🚨 URGENT: Cara Mengaktifkan WhatsApp Notification

## ❌ Masalah Saat Ini

WhatsApp notification **TIDAK AKTIF** karena:

```bash
WHATSAPP_API_TOKEN=null        ❌
WHATSAPP_ENABLED=false         ❌
```

---

## ✅ Langkah Perbaikan (5 Menit)

### Step 1: Buka File `.env`

Buka file `.env` di root project Anda.

### Step 2: Tambahkan Konfigurasi WhatsApp

**Tambahkan** baris berikut di bagian bawah file `.env`:

```env
# WhatsApp Configuration (Fonnte)
WHATSAPP_API_URL=https://api.fonnte.com/send
WHATSAPP_API_TOKEN=YOUR_FONNTE_TOKEN_HERE
WHATSAPP_ENABLED=true
```

**⚠️ PENTING:**

- Ganti `YOUR_FONNTE_TOKEN_HERE` dengan token Fonnte Anda yang sudah ada
- Pastikan `WHATSAPP_ENABLED=true` (bukan false!)

### Step 3: Dapatkan Token Fonnte (Jika Belum Punya)

1. Login ke dashboard Fonnte: https://fonnte.com
2. Buka menu **Account** → **API**
3. Copy **API Token** Anda
4. Paste ke `.env`

**Contoh:**

```env
WHATSAPP_API_TOKEN=abcd1234@efgh5678
```

### Step 4: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 5: Verify Konfigurasi

```bash
php artisan config:show services.whatsapp
```

**Expected Output:**

```
services.whatsapp
  api_url ........................................ https://api.fonnte.com/send
  token ................................................................. abcd1234@efgh5678  ✅
  enabled .............................................................. true  ✅
```

---

## 🧪 Test WhatsApp

### Test via Route

Buka browser:

```
http://localhost/test-whatsapp?phone=628123456789
```

**Ganti** `628123456789` dengan nomor HP Anda!

### Expected Response:

```json
{
  "success": true,
  "message": "Message sent",
  "phone": "628123456789",
  "response": {
    "status": true
  }
}
```

Cek WhatsApp → Anda akan terima pesan test! ✅

---

## 🔧 Troubleshooting

### Still "WhatsApp is disabled"?

1. Check `.env` file - ada `WHATSAPP_ENABLED=true`?
2. Run `php artisan config:clear` lagi
3. Restart server: `php artisan serve`

### Still "Token not configured"?

1. Check `.env` - token ada tanda kutip? **JANGAN pakai kutip!**

   ❌ Wrong:

   ```env
   WHATSAPP_API_TOKEN="abcd1234"
   ```

   ✅ Correct:

   ```env
   WHATSAPP_API_TOKEN=abcd1234
   ```

2. Check token di Fonnte dashboard - copy ulang

### Test Failed?

Checklist:

1. ✅ Fonnte device connected?
2. ✅ Token benar?
3. ✅ Nomor HP format 62xxx?
4. ✅ Quota Fonnte masih ada?

---

## 📱 Integrasi dengan intl-tel-input

Karena Anda sudah pakai `intl-tel-input` di form muzakki/edit, mari kita integrasikan ke form donasi guest juga!

### File: `resources/views/payments/guest-create.blade.php`

Sudah ada implementasi basic. Untuk support country code selain +62, update:

**Tambahkan di `@push('styles')`:**

```html
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css"
/>
```

**Tambahkan di `@push('scripts')`:**

```html
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script>
  // Initialize intl-tel-input for guest form
  const guestPhoneInput = document.querySelector("#donor_phone");
  if (guestPhoneInput) {
    const iti = window.intlTelInput(guestPhoneInput, {
      initialCountry: "id",
      separateDialCode: true,
      utilsScript:
        "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
      preferredCountries: ["id", "my", "sg"],
      onlyCountries: [
        "id",
        "my",
        "sg",
        "ph",
        "th",
        "vn",
        "bn",
        "mm",
        "kh",
        "la",
      ],
    });

    // On form submit, get full international number
    document
      .getElementById("donation-form")
      .addEventListener("submit", function (e) {
        const fullNumber = iti.getNumber();
        guestPhoneInput.value = fullNumber;
      });
  }
</script>
```

---

## 📊 Check Log untuk Debugging

### Laravel Log

```bash
tail -f storage/logs/laravel.log
```

Look for:

```
[INFO] No phone number for muzakki, skip WhatsApp notification
[INFO] WhatsApp is disabled
[ERROR] WhatsApp API token not configured
```

### WhatsApp Log

```bash
tail -f storage/logs/whatsapp.log
```

Expected after fix:

```
[INFO] WhatsApp message sent
{
  "phone": "628123456789",
  "payment_code": "ZKT-2025-1015",
  "success": true
}
```

---

## 🎯 Kenapa WhatsApp Tidak Terkirim Sebelumnya?

1. ❌ `.env` tidak ada konfigurasi WhatsApp
2. ❌ `WHATSAPP_ENABLED=false` (default)
3. ❌ `WHATSAPP_API_TOKEN=null`
4. ❌ Observer skip karena config disabled

**Alur yang seharusnya:**

```
Payment Completed
    ↓
Observer Triggered
    ↓
Check WhatsApp Config
    ├─ Enabled? ✅
    ├─ Token? ✅
    └─ Phone? ✅
    ↓
Send WhatsApp ✅
```

**Alur saat ini (sebelum fix):**

```
Payment Completed
    ↓
Observer Triggered
    ↓
Check WhatsApp Config
    ├─ Enabled? ❌ FALSE
    └─ STOP (WhatsApp disabled)
```

---

## 🚀 After Fix

Setelah set `.env` dan clear cache:

1. **Buat payment baru**
2. **Bayar via Midtrans**
3. **Status → Completed**
4. **Observer triggered**
5. **Email sent** ✅
6. **WhatsApp sent** ✅

Check WhatsApp Anda → pesan masuk! 🎉

---

## 💡 Tips

### Test dengan Payment Existing

Jika ada payment yang sudah completed tapi belum terima WA:

```bash
php artisan tinker
```

Then:

```php
$payment = \App\Models\ZakatPayment::where('payment_code', 'ZKT-2025-1015')->first();
$whatsapp = new \App\Services\WhatsAppService();
$result = $whatsapp->sendPaymentSuccess($payment, $payment->muzakki->phone);
print_r($result);
```

### Monitor Real-time

```bash
# Terminal 1: Laravel logs
tail -f storage/logs/laravel.log

# Terminal 2: WhatsApp logs
tail -f storage/logs/whatsapp.log

# Terminal 3: Run server
php artisan serve
```

---

**INGAT**: Setelah update `.env`, **SELALU** run `php artisan config:clear`!

---

**Status Sekarang**: ❌ WhatsApp DISABLED  
**Status Setelah Fix**: ✅ WhatsApp ACTIVE

**Estimated Time**: 5 minutes  
**Difficulty**: Easy ⭐

---

Good luck! 🚀
