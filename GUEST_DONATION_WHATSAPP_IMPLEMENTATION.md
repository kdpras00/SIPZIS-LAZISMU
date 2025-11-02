# 📱 Implementasi Donasi Guest User & Notifikasi WhatsApp

## 🎯 Overview

Dokumen ini menjelaskan implementasi lengkap untuk:
1. **Guest User Donation** - User yang belum login dapat berdonasi
2. **WhatsApp Notification** - Notifikasi dikirim via WhatsApp & Email
3. **Phone Number Validation** - Handling user tanpa nomor HP

---

## 🔄 Alur Donasi Guest User

### Skenario 1: Guest User (Tidak Login)

```
1. User mengakses halaman donasi program/campaign
   └─> URL: /guest/payment/create?program_id=X&category=pendidikan

2. Form donasi menampilkan field:
   ├─> Nominal donasi (required)
   ├─> Nama lengkap (required)
   ├─> Nomor HP/WhatsApp (required) ← WAJIB untuk notifikasi WA
   ├─> Email (required)
   └─> Pesan/Doa (optional)

3. User mengisi form dan submit
   ├─> Validasi: Nama, HP, Email harus diisi
   └─> Nomor HP diformat otomatis (62xxx)

4. Sistem membuat/update data muzakki
   ├─> Cek email sudah ada? → Update data
   └─> Email baru? → Create muzakki baru

5. Payment record dibuat dengan flag is_guest_payment = true

6. User diarahkan ke halaman pembayaran
   └─> Pilih metode pembayaran (VA/E-wallet/QRIS)

7. Setelah pembayaran:
   ├─> Status: Pending → Email & WA sent ✉️📱
   ├─> Status: Completed → Email & WA sent ✅
   └─> Status: Failed → Email & WA sent ❌
```

### Skenario 2: User Login DENGAN Nomor HP

```
1. User login dan sudah punya nomor HP di profile
   
2. Akses form donasi
   ├─> Field nama, email, HP auto-filled
   └─> User hanya isi nominal & pesan

3. Submit donasi → menggunakan data profile

4. Notifikasi dikirim ke:
   ├─> Email: user@email.com ✉️
   └─> WhatsApp: 628123456789 📱
```

### Skenario 3: User Login TANPA Nomor HP

```
1. User login tapi belum isi nomor HP di profile

2. Akses form donasi
   └─> Muncul ALERT/MODAL:
       "⚠️ Lengkapi nomor HP untuk notifikasi WhatsApp"
       [Lengkapi Sekarang] [Lanjut Tanpa WA]

3a. Pilih "Lengkapi Sekarang"
    └─> Redirect ke halaman edit profile
        └─> Tab Phone Number highlighted
        └─> Setelah simpan → Kembali ke form donasi

3b. Pilih "Lanjut Tanpa WA"
    └─> Form donasi menampilkan input nomor HP
    ├─> User bisa isi nomor HP baru
    ├─> Atau skip (notif hanya email)
    └─> Nomor HP yang diisi akan update profile

4. Submit donasi:
   ├─> Ada nomor HP? → Email & WA ✉️📱
   └─> Tidak ada HP? → Email saja ✉️
```

---

## 📋 Field Database yang Diperlukan

### Table: `muzakki`
```sql
- phone VARCHAR(20) NULLABLE -- Format: 628123456789
- phone_verified BOOLEAN DEFAULT FALSE
- email VARCHAR(255) NULLABLE
```

### Table: `zakat_payments`
```sql
- is_guest_payment BOOLEAN DEFAULT FALSE
- muzakki_id FOREIGN KEY
- donor_phone VARCHAR(20) NULLABLE -- Backup jika muzakki tidak punya HP
```

---

## 🔧 Implementasi Teknis

### 1. WhatsApp Service Provider

Kami menggunakan **Fonnte.com** untuk WhatsApp API (alternatif: Twilio, WABLAS)

**Alasan memilih Fonnte:**
- ✅ Mudah setup
- ✅ Harga terjangkau
- ✅ Support WhatsApp Business API
- ✅ Dokumentasi lengkap dalam Bahasa Indonesia

### 2. Setup WhatsApp API

**File: `.env`**
```env
# WhatsApp Configuration (Fonnte)
WHATSAPP_API_URL=https://api.fonnte.com/send
WHATSAPP_API_TOKEN=your_fonnte_token_here
WHATSAPP_ENABLED=true
```

**File: `config/services.php`**
```php
'whatsapp' => [
    'api_url' => env('WHATSAPP_API_URL', 'https://api.fonnte.com/send'),
    'token' => env('WHATSAPP_API_TOKEN'),
    'enabled' => env('WHATSAPP_ENABLED', false),
],
```

### 3. WhatsApp Service Class

**File: `app/Services/WhatsAppService.php`**

Menangani pengiriman pesan WhatsApp dengan berbagai template.

### 4. Form Validation Logic

**Guest User Form:**
```javascript
// Validasi nomor HP wajib untuk guest
donor_phone: required|regex:/^(08|628)[0-9]{8,13}$/
```

**Logged User Without Phone:**
```php
// Cek apakah user punya nomor HP
if (Auth::check() && !Auth::user()->muzakki->phone) {
    // Show modal/alert untuk lengkapi profile
    return redirect()->route('muzakki.edit', $muzakki->id)
                    ->with('warning', 'Lengkapi nomor HP untuk notifikasi WhatsApp');
}
```

### 5. Notification Logic

**File: `app/Observers/ZakatPaymentObserver.php`**

```php
public function updated(ZakatPayment $payment) {
    if ($payment->isDirty('status')) {
        // Kirim Email
        $this->sendPaymentEmail($payment);
        
        // Kirim WhatsApp (jika ada nomor HP)
        $this->sendWhatsAppNotification($payment);
    }
}

private function sendWhatsAppNotification($payment) {
    $phone = $payment->muzakki->phone ?? $payment->donor_phone;
    
    if (!$phone) {
        Log::info('No phone number, skip WhatsApp notification');
        return;
    }
    
    $whatsappService = new WhatsAppService();
    
    switch ($payment->status) {
        case 'completed':
            $whatsappService->sendPaymentSuccess($payment, $phone);
            break;
        case 'pending':
            $whatsappService->sendPaymentPending($payment, $phone);
            break;
        case 'failed':
            $whatsappService->sendPaymentFailed($payment, $phone);
            break;
    }
}
```

---

## 📱 Template Pesan WhatsApp

### 1. Payment Pending
```
🕐 *DONASI PENDING*

Halo *{nama}*,

Terima kasih telah berdonasi melalui SIPZIS!

📋 Detail Donasi:
• Kode: {payment_code}
• Program: {program_name}
• Nominal: Rp {amount}
• Status: Menunggu Pembayaran

💳 Silakan selesaikan pembayaran Anda.

Cek status: {tracking_url}

_SIPZIS - Sistem Zakat_
```

### 2. Payment Success
```
✅ *DONASI BERHASIL*

Alhamdulillah! 🎉

Halo *{nama}*,

Donasi Anda telah berhasil diterima.

📋 Detail Donasi:
• Kode: {payment_code}
• Program: {program_name}
• Nominal: Rp {amount}
• Tanggal: {date}

Jazakallahu khairan katsiran! 🤲

Bukti donasi telah dikirim ke email Anda.

_SIPZIS - Sistem Zakat_
```

### 3. Payment Failed
```
❌ *DONASI GAGAL*

Halo *{nama}*,

Maaf, pembayaran Anda gagal diproses.

📋 Detail:
• Kode: {payment_code}
• Nominal: Rp {amount}

Silakan coba lagi atau hubungi kami.

🔄 Donasi Ulang: {retry_url}
📞 Bantuan: {support_contact}

_SIPZIS - Sistem Zakat_
```

---

## 🎨 UI/UX Implementation

### Modal: Lengkapi Nomor HP

**File: `resources/views/components/phone-required-modal.blade.php`**

```blade
<!-- Modal muncul jika user login tanpa HP -->
<div id="phoneRequiredModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Lengkapi Nomor HP
                </h3>
                
                <p class="text-sm text-gray-600 mb-6">
                    Untuk menerima notifikasi WhatsApp tentang status donasi Anda, silakan lengkapi nomor HP terlebih dahulu.
                </p>
                
                <div class="flex flex-col gap-3">
                    <a href="{{ route('muzakki.edit', Auth::user()->muzakki->id) }}" 
                       class="w-full bg-green-600 text-white rounded-lg px-4 py-3 font-semibold hover:bg-green-700 transition">
                        📱 Lengkapi Sekarang
                    </a>
                    
                    <button onclick="continueWithoutPhone()" 
                            class="w-full bg-gray-200 text-gray-700 rounded-lg px-4 py-3 font-semibold hover:bg-gray-300 transition">
                        ⏭️ Lanjut Tanpa WhatsApp
                    </button>
                </div>
                
                <p class="text-xs text-gray-500 mt-4">
                    * Anda tetap akan menerima notifikasi via email
                </p>
            </div>
        </div>
    </div>
</div>
```

### Form Enhancement

**File: `resources/views/payments/guest-create.blade.php`**

Tambahkan validasi real-time untuk nomor HP:

```javascript
// Validasi format nomor HP Indonesia
function validatePhoneNumber(input) {
    let value = input.value.replace(/\D/g, ''); // Remove non-digits
    
    // Format ke 62xxx jika mulai dengan 08
    if (value.startsWith('08')) {
        value = '62' + value.substring(1);
    }
    
    // Validasi panjang
    if (value.length < 10 || value.length > 15) {
        input.setCustomValidity('Nomor HP harus 10-15 digit');
    } else if (!value.startsWith('62')) {
        input.setCustomValidity('Nomor HP harus dimulai dengan 62 atau 08');
    } else {
        input.setCustomValidity('');
    }
    
    input.value = value;
}
```

---

## 🔒 Security & Privacy

### 1. Phone Number Privacy
- Nomor HP tidak ditampilkan penuh di UI publik
- Format tampilan: `+62812****5678`

### 2. Opt-out Mechanism
- User bisa disable notifikasi WhatsApp di profile
- Field: `notification_preferences` (JSON)

```json
{
  "email": true,
  "whatsapp": false
}
```

### 3. Rate Limiting
- Max 5 notifikasi WhatsApp per user per hari
- Prevent spam dan abuse

---

## 📊 Analytics & Logging

### Log setiap pengiriman notifikasi:

```php
Log::channel('whatsapp')->info('WhatsApp sent', [
    'recipient' => $phone,
    'payment_code' => $payment->payment_code,
    'status' => $payment->status,
    'sent_at' => now(),
    'success' => $response->success
]);
```

### Tracking metrics:
- ✅ Delivery rate (berhasil/gagal kirim)
- 📊 Open rate (jika API support)
- ⏱️ Response time

---

## 🧪 Testing Checklist

### Unit Tests
- [ ] WhatsAppService mengirim pesan dengan format benar
- [ ] Phone number formatting (08xxx → 62xxx)
- [ ] Validasi nomor HP invalid

### Integration Tests
- [ ] Guest user donasi tanpa login
- [ ] User login dengan HP → terima WA & Email
- [ ] User login tanpa HP → terima Email saja
- [ ] Modal muncul jika user tanpa HP
- [ ] Update HP di profile → WA aktif

### Manual Testing
- [ ] Test kirim WA ke nomor real
- [ ] Cek template pesan muncul dengan benar
- [ ] Test semua status (pending, completed, failed)
- [ ] Test opt-out WhatsApp notification

---

## 📚 API Alternatives

### Jika Fonnte tidak cocok:

#### 1. **Twilio WhatsApp API**
- Pro: Reliable, enterprise-grade
- Con: Lebih mahal
- Setup: Perlu approval WhatsApp Business

#### 2. **WABLAS**
- Pro: Mudah, support Indonesia
- Con: Perlu hosting sendiri

#### 3. **WooWA (WhatsApp Gateway)**
- Pro: Gratis (self-hosted)
- Con: Butuh HP Android dedicated

---

## 🎓 Best Practices

1. **Always validate phone numbers** sebelum kirim
2. **Fallback to email** jika WA gagal
3. **Log semua notifikasi** untuk audit trail
4. **Respect user preferences** (opt-out)
5. **Test di production** dengan nomor test dulu
6. **Monitor API quota** untuk avoid overcharge

---

## 📞 Support & Troubleshooting

### WhatsApp tidak terkirim?

**Checklist:**
1. ✅ API token valid?
2. ✅ Nomor HP format benar (62xxx)?
3. ✅ Quota API masih ada?
4. ✅ WhatsApp enabled di .env?
5. ✅ Log error di storage/logs/whatsapp.log?

### User tidak terima notifikasi?

**Kemungkinan:**
1. Nomor HP salah/tidak aktif
2. User block nomor pengirim
3. WhatsApp tidak terinstall
4. Fallback: Kirim email tetap jalan

---

## 🚀 Deployment Checklist

- [ ] Setup API WhatsApp (Fonnte/Twilio)
- [ ] Update .env dengan token
- [ ] Run migration (jika ada perubahan DB)
- [ ] Test send WA di staging
- [ ] Deploy ke production
- [ ] Monitor logs 24 jam pertama
- [ ] Dokumentasi ke tim support

---

**Last Updated:** 2025-10-29
**Version:** 1.0.0
**Author:** SIPZIS Development Team

