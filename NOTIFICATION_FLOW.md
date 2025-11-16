# Flow Notifikasi SIPZIS

Dokumentasi lengkap tentang flow notifikasi untuk semua jenis notifikasi di sistem SIPZIS.

## 📋 Daftar Notifikasi

### 1. ✅ **Notifikasi Pembayaran (Payment)**
**Status:** Sudah bekerja otomatis

**Flow:**
- **Trigger:** Saat pembayaran zakat/infaq/shadaqah dibuat atau status berubah
- **Lokasi:** `ZakatPayment::boot()` - Model Observer
- **Penerima:** Muzakki yang melakukan pembayaran
- **Jenis Pesan:**
  - `completed`: "Pembayaran [Zakat/Infaq/Shadaqah/Program Pilar] Anda telah berhasil diverifikasi."
  - `pending`: "Menunggu konfirmasi pembayaran [Jenis] melalui [Metode]."
  - `failed`: "Pembayaran [Jenis] Anda gagal diproses, silakan coba kembali."

**Cara Kerja:**
- Otomatis dibuat saat `ZakatPayment` dibuat atau diupdate
- Deteksi jenis pembayaran berdasarkan `program_category`
- Notifikasi langsung masuk ke `muzakki_id` yang melakukan pembayaran

---

### 2. ✅ **Notifikasi Distribusi (Distribution)**
**Status:** Sudah bekerja otomatis

**Flow:**
- **Trigger:** Saat admin membuat distribusi zakat baru
- **Lokasi:** `ZakatDistributionController::store()`
- **Penerima:** Semua muzakki yang pernah melakukan pembayaran completed
- **Pesan:** "Zakat Anda telah disalurkan kepada mustahik di wilayah [lokasi]."

**Cara Kerja:**
- Saat distribusi dibuat, sistem mencari semua muzakki yang pernah melakukan pembayaran completed
- Notifikasi dibuat untuk setiap muzakki
- Semua muzakki mendapat informasi bahwa zakat mereka telah disalurkan

---

### 3. ✅ **Notifikasi Akun (Account)**
**Status:** Sudah bekerja otomatis

**Flow:**
- **Trigger:** 
  - Saat user baru register (login biasa)
  - Saat user baru login pertama kali dengan Google/Firebase
  - Saat password diubah
- **Lokasi:** 
  - `AuthController::register()` - Registrasi baru
  - `AuthController::login()` - Login pertama kali
  - `AuthController::firebaseLogin()` - Login Google/Firebase pertama kali
  - `User::boot()` - Perubahan password
- **Penerima:** User yang melakukan aksi tersebut
- **Jenis Pesan:**
  - `profile`: "Selamat datang! Lengkapi profil Anda untuk mempermudah transaksi donasi."
  - `password`: "Kata sandi Anda berhasil diubah."

**Cara Kerja:**
- **Registrasi Baru:** Notifikasi dibuat langsung setelah muzakki dibuat
- **Login Pertama Kali:** Sistem cek apakah sudah ada notifikasi account, jika belum maka dibuat
- **Login Google/Firebase:** Cek `wasRecentlyCreated`, jika user baru maka buat notifikasi
- **Password Change:** Otomatis saat password diubah

---

### 4. ✅ **Notifikasi Program (Program)**
**Status:** Sudah bekerja otomatis

**Flow:**
- **Trigger:** 
  - Saat program baru dibuat dengan status 'active'
  - Saat program diupdate dan status berubah menjadi 'active'
- **Lokasi:** `Program::boot()` - Model Observer
- **Penerima:** Semua muzakki yang terdaftar di sistem
- **Pesan:** "Program [Nama Program] telah tersedia. Mari berpartisipasi dalam program ini!"

**Cara Kerja:**
- Saat program dibuat/diaktifkan, sistem mengambil semua muzakki
- Notifikasi dibuat untuk setiap muzakki
- Semua muzakki mendapat informasi tentang program baru

---

### 5. ⚠️ **Notifikasi Pengingat (Reminder)**
**Status:** Method ada, belum dipanggil otomatis

**Flow:**
- **Trigger:** Manual atau Scheduled Job (belum diimplementasikan)
- **Lokasi:** `Notification::createReminderNotification()`
- **Penerima:** Muzakki tertentu (dipilih manual atau berdasarkan kondisi)
- **Jenis Pesan:**
  - `zakat`: "Sudah waktunya membayar zakat penghasilan bulan ini."
  - `balance`: "Saldo zakat Anda tersisa Rp200.000, ingin disalurkan?"

**Cara Kerja (Saat Ini):**
- Method sudah tersedia
- Perlu dipanggil manual atau melalui scheduled job
- Contoh penggunaan:
  ```php
  $muzakki = Muzakki::find($id);
  Notification::createReminderNotification($muzakki, 'zakat');
  ```

**Rekomendasi Implementasi:**
- Buat scheduled job untuk mengirim reminder zakat bulanan
- Buat scheduled job untuk mengingatkan saldo zakat yang tersisa

---

### 6. ⚠️ **Notifikasi Pesan (Message)**
**Status:** Method ada, belum dipanggil otomatis

**Flow:**
- **Trigger:** Manual (belum diimplementasikan)
- **Lokasi:** `Notification::createMessageNotification()`
- **Penerima:** User tertentu (dipilih manual)
- **Pesan:** "[Sender]: [Pesan]"

**Cara Kerja (Saat Ini):**
- Method sudah tersedia
- Perlu dipanggil manual
- Contoh penggunaan:
  ```php
  $user = User::find($id);
  Notification::createMessageNotification($user, 'Pesan dari admin', 'Admin');
  ```

**Rekomendasi Implementasi:**
- Buat fitur admin untuk mengirim pesan ke muzakki
- Buat fitur broadcast message ke semua muzakki

---

## 🔄 Flow Lengkap Notifikasi

### **Login Biasa (Email/Password)**
1. User register → Notifikasi "Selamat Datang" dibuat ✅
2. User login pertama kali → Cek notifikasi account, jika belum ada maka buat ✅
3. User login berikutnya → Tidak ada notifikasi baru

### **Login Google/Firebase**
1. User login pertama kali dengan Google → Cek `wasRecentlyCreated`, jika baru maka buat notifikasi ✅
2. User login berikutnya → Tidak ada notifikasi baru

### **Pembayaran**
1. User membuat pembayaran → Notifikasi "Menunggu Konfirmasi" ✅
2. Admin verifikasi pembayaran → Notifikasi "Pembayaran Berhasil" ✅
3. Pembayaran gagal → Notifikasi "Pembayaran Gagal" ✅

### **Distribusi**
1. Admin membuat distribusi → Semua muzakki mendapat notifikasi ✅

### **Program**
1. Admin membuat program baru (status active) → Semua muzakki mendapat notifikasi ✅
2. Admin mengaktifkan program → Semua muzakki mendapat notifikasi ✅

---

## 📊 Status Implementasi

| Jenis Notifikasi | Status | Otomatis | Manual | Catatan |
|------------------|--------|----------|--------|---------|
| Payment | ✅ | ✅ | - | Sudah bekerja |
| Distribution | ✅ | ✅ | - | Sudah bekerja |
| Account | ✅ | ✅ | - | Sudah bekerja (register & login pertama) |
| Program | ✅ | ✅ | - | Sudah bekerja |
| Reminder | ⚠️ | ❌ | ✅ | Method ada, perlu scheduled job |
| Message | ⚠️ | ❌ | ✅ | Method ada, perlu fitur admin |

---

## 🎯 Kesimpulan

**Notifikasi yang sudah bekerja otomatis:**
- ✅ Pembayaran (payment)
- ✅ Distribusi (distribution)
- ✅ Akun (account) - untuk register dan login pertama kali
- ✅ Program (program)

**Notifikasi yang perlu implementasi lebih lanjut:**
- ⚠️ Pengingat (reminder) - perlu scheduled job
- ⚠️ Pesan (message) - perlu fitur admin

**Semua notifikasi yang sudah bekerja akan langsung masuk ke muzzaki** baik yang login dengan Google maupun login biasa, karena semua menggunakan `muzakki_id` yang sama.

