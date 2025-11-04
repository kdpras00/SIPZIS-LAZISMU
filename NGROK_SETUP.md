# Setup Ngrok untuk Development HTTPS

## Masalah
Ketika menggunakan ngrok dengan HTTPS, browser memblokir Mixed Content karena:
- Ngrok menggunakan HTTPS
- Vite dev server menggunakan HTTP (localhost:5173)
- Browser tidak mengizinkan HTTPS page memuat HTTP resources

## Solusi 1: Build Production Assets (DISARANKAN)

Ini adalah solusi termudah dan terbaik untuk ngrok HTTPS:

```bash
# Build assets production
npm run build

# Jalankan Laravel
php artisan serve
# atau
php artisan serve --host=0.0.0.0 --port=8000

# Setup ngrok untuk forward ke port 8000
ngrok http 8000
```

**Keuntungan:**
- ✅ Tidak ada masalah Mixed Content
- ✅ Tidak perlu setup tambahan
- ✅ Assets sudah di-compile dan siap production

**Kekurangan:**
- ❌ Perlu rebuild setiap kali ada perubahan CSS/JS
- ❌ Tidak ada Hot Module Replacement (HMR)

## Solusi 2: Setup Ngrok untuk Forward Vite Juga

Jika ingin tetap menggunakan dev mode dengan HMR:

### Langkah 1: Setup ngrok untuk forward 2 port
```bash
# Terminal 1: Jalankan Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: Jalankan Vite
npm run dev

# Terminal 3: Setup ngrok untuk Laravel
ngrok http 8000

# Terminal 4: Setup ngrok untuk Vite (dapatkan URL dari ngrok dashboard atau gunakan ngrok config)
ngrok http 5173
```

### Langkah 2: Set Environment Variable

Setelah mendapat URL ngrok untuk Vite, tambahkan ke `.env`:

```env
VITE_APP_URL=https://your-vite-ngrok-url.ngrok-free.app
```

### Langkah 3: Restart Vite
```bash
npm run dev
```

## Solusi 3: Gunakan HTTP Ngrok (Alternatif)

Jika tidak memerlukan HTTPS, gunakan HTTP ngrok:

```bash
ngrok http 8000 --scheme=http
```

Ini akan menghindari masalah Mixed Content, tapi tidak ada enkripsi.

## Catatan

- Favicon sudah diperbaiki untuk menggunakan `secure_asset()` 
- AppServiceProvider sudah dikonfigurasi untuk force HTTPS saat request HTTPS
- Vite config sudah diset untuk allow CORS dan ngrok domains

## Troubleshooting

Jika masih ada masalah:

1. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Restart semua services:**
   - Stop dan restart Laravel server
   - Stop dan restart Vite dev server
   - Restart ngrok

3. **Cek `.env`:**
   Pastikan `APP_URL` sesuai dengan ngrok URL:
   ```env
   APP_URL=https://your-ngrok-url.ngrok-free.app
   ```

