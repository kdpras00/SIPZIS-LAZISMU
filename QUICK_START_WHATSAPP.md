# 🚀 Quick Start: WhatsApp Notification untuk SIPZIS

## ⚡ Setup dalam 5 Menit

### Step 1: Setup Fonnte Account (2 menit)

1. **Buka** https://fonnte.com
2. **Daftar** dengan email Anda
3. **Login** ke dashboard
4. **Tambah Device:**
   - Menu: Device → Add Device
   - Scan QR Code dengan WhatsApp yang mau dijadikan sender
5. **Copy API Token:**
   - Menu: Account → API
   - Copy token (format: `xxxx@xxxxxxx`)

### Step 2: Konfigurasi Laravel (1 menit)

Buka file `.env` dan tambahkan:

```env
WHATSAPP_API_URL=https://api.fonnte.com/send
WHATSAPP_API_TOKEN=xxxx@xxxxxxx
WHATSAPP_ENABLED=true
```

**Ganti `xxxx@xxxxxxx` dengan token Anda!**

### Step 3: Clear Cache (30 detik)

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 4: Test Connection (1 menit)

Buka di browser:

```
http://localhost/test-whatsapp?phone=628123456789
```

**Ganti `628123456789` dengan nomor HP Anda!**

Jika berhasil, Anda akan menerima pesan WhatsApp test! ✅

### Step 5: Test Donation Flow (1 menit)

1. Buka form donasi guest: `http://localhost/guest/payment/create`
2. Isi form dengan data test:
   - Nama: Test User
   - HP: 81234567890 (otomatis jadi 62xxx)
   - Email: test@email.com
   - Nominal: 50000
3. Submit dan lakukan pembayaran
4. Cek WhatsApp & Email → Notifikasi diterima! 🎉

---

## ✅ Selesai!

WhatsApp notification sudah aktif untuk:

- ✅ Payment Pending
- ✅ Payment Success
- ✅ Payment Failed
- ✅ Payment Cancelled

---

## 🆘 Troubleshooting

### Tidak terima WhatsApp?

**Checklist:**

1. Token sudah benar?
2. WhatsApp device masih connect di Fonnte?
3. Nomor HP format benar (62xxx)?
4. WHATSAPP_ENABLED=true?
5. Sudah clear cache?

### Cek Log

```bash
# Windows
type storage\logs\whatsapp.log

# Linux/Mac
tail -f storage/logs/whatsapp.log
```

---

## 📚 Dokumentasi Lengkap

- **GUEST_DONATION_WHATSAPP_IMPLEMENTATION.md** - Dokumentasi teknis lengkap
- **WHATSAPP_SETUP_GUIDE.md** - Panduan setup detail
- **IMPLEMENTATION_SUMMARY.md** - Ringkasan implementasi

---

## 💡 Tips

- **Free Plan Fonnte:** 100 pesan/bulan
- **Nomor Format:** Selalu gunakan 62xxx (Indonesia)
- **Monitoring:** Cek Fonnte dashboard untuk quota & delivery status
- **Production:** Setup monitoring alert jika quota hampir habis

---

**Happy Coding!** 🚀

_SIPZIS Development Team_
