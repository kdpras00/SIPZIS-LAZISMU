# 🔧 Fix: Summary Page Loading Issue (Muter-Muter)

## 🐛 Masalah yang Dilaporkan

User melaporkan beberapa issue:

1. **Summary page muter-muter terus** - Tidak redirect ke success page
2. **JavaScript Error**: `Cannot read properties of null (reading 'addEventListener')` pada line 390
3. **Console log**: Status masih "pending" terus meskipun sudah bayar
4. **Lama menuju success page**: Harus tunggu lama untuk ke halaman success

## 🔍 Root Cause

### 1. JavaScript Error - Element Not Found
```javascript
// ❌ BEFORE - Error jika element tidak ada
checkStatusButton.addEventListener('click', async () => {
    // ...
});
```

**Penyebab**: Element `checkStatusButton` atau `leavePageButton` mungkin tidak ada di halaman tertentu (misal: sudah completed), tapi code tetap coba add event listener.

### 2. Auto-Check Terlalu Cepat & Tidak Efisien
```javascript
// ❌ BEFORE - Check tiap 3 detik tanpa batas
setInterval(checkPaymentStatus, 3000);
```

**Masalah**:
- Terlalu sering check (3 detik) → overload server
- Tidak ada batas maksimum → infinite loop
- Tidak ada logging yang jelas

### 3. Route `checkStatus` Tidak Ada
```php
// ❌ Route tidak didefinisikan
Route::get('/check-status/{paymentCode}', ...)
```

**Penyebab**: Frontend memanggil route yang belum dibuat.

### 4. Midtrans Webhook Delay
- Midtrans bisa delay kirim notifikasi
- Manual check perlu call Midtrans API langsung
- Perlu fallback mechanism

---

## ✅ Solusi yang Diterapkan

### 1. Fix JavaScript Error - Null Check

**File**: `resources/views/payments/guest-summary.blade.php`

```javascript
// ✅ AFTER - Check element exists first
if (checkStatusButton) {
    checkStatusButton.addEventListener('click', async () => {
        const res = await fetch('{{ route("guest.payment.checkStatus", $payment->payment_code) }}');
        const data = await res.json();
        if (data.success) showNotification(data.message, 'info');
        else showNotification('Gagal cek status', 'error');
    });
}

if (leavePageButton) {
    leavePageButton.addEventListener('click', async () => {
        // ...
    });
}
```

**Benefits**:
- ✅ Tidak error jika element tidak ada
- ✅ Code lebih defensive
- ✅ Compatibility dengan berbagai status page

### 2. Improve Auto-Check Logic

```javascript
// ✅ AFTER - Better auto-check dengan limits
const maxChecks = 60; // Max 5 minutes (60 * 5 seconds)
let checkCount = 0;

async function checkPaymentStatus() {
    try {
        checkCount++;
        console.log(`Checking payment status... (${checkCount}/${maxChecks})`);
        
        const res = await fetch(checkUrl);
        const data = await res.json();
        
        console.log('Payment status:', data);
        
        if (data.status === 'completed') {
            console.log('Payment completed! Redirecting...');
            window.location.href = "{{ route('guest.payment.success', $payment->payment_code) }}";
        } else if (data.status === 'cancelled' || data.status === 'failed') {
            console.log('Payment cancelled/failed! Redirecting...');
            window.location.href = "{{ route('guest.payment.failed', $payment->payment_code) }}";
        } else if (checkCount >= maxChecks) {
            // Stop after max attempts
            showNotification('Pembayaran masih pending. Silakan refresh halaman atau hubungi support.', 'warning');
            clearInterval(statusCheckInterval);
        }
    } catch (e) {
        console.error('Error checking payment status:', e);
    }
}

// Initial check after 2 seconds
setTimeout(checkPaymentStatus, 2000);

// Then check every 5 seconds
const statusCheckInterval = setInterval(checkPaymentStatus, 5000);
```

**Improvements**:
- ✅ Check interval: 3s → 5s (less server load)
- ✅ Max checks: 60 attempts (5 minutes total)
- ✅ Better logging: Track progress
- ✅ Auto-stop after max attempts
- ✅ User notification when max reached
- ✅ Initial check after 2s (faster first response)

### 3. Add Missing Routes

**File**: `routes/web.php`

```php
// ✅ AFTER - Routes added
Route::prefix('donasi')->name('guest.payment.')->group(function () {
    // ... existing routes ...
    
    // Check payment status via AJAX
    Route::get('/check-status/{paymentCode}', [ZakatPaymentController::class, 'guestCheckStatus'])
        ->name('checkStatus');
    
    // Leave page handler
    Route::post('/leave-page/{paymentCode}', [ZakatPaymentController::class, 'guestLeavePage'])
        ->name('leavePage');
});
```

### 4. Enhance Controller Methods

**File**: `app/Http/Controllers/ZakatPaymentController.php`

```php
/**
 * Check payment status (calls Midtrans API)
 */
public function guestCheckStatus($paymentCode)
{
    try {
        $payment = ZakatPayment::where('payment_code', $paymentCode)
            ->where('is_guest_payment', true)
            ->firstOrFail();

        // Call Midtrans API untuk status real-time
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        $orderId = $payment->midtrans_order_id ?? $payment->payment_code;
        $status = \Midtrans\Transaction::status($orderId);

        // Update payment status based on Midtrans response
        $newStatus = $this->mapMidtransStatus($status);
        
        if ($newStatus && $payment->status !== $newStatus) {
            $payment->update(['status' => $newStatus]);
            Log::info("Payment status updated from Midtrans: {$payment->payment_code} → {$newStatus}");
        }

        return response()->json([
            'success' => true,
            'status' => $payment->status,
            'message' => "Status: {$payment->status}",
            'midtrans_status' => $status->transaction_status ?? null
        ]);
    } catch (\Exception $e) {
        Log::error('Check Status Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to check status'
        ], 500);
    }
}
```

**Benefits**:
- ✅ Call Midtrans API langsung → real-time status
- ✅ Auto-update payment jika ada perubahan
- ✅ Return detailed response untuk debugging
- ✅ Proper error handling

---

## 🧪 Testing

### Test 1: JavaScript Error Fix
```
1. Buka summary page dengan berbagai status
2. Check console - should have NO errors
3. ✅ No more "Cannot read properties of null"
```

### Test 2: Auto-Check Status
```
1. Buat payment baru (pending)
2. Buka summary page
3. Console log should show:
   - "Checking payment status... (1/60)"
   - "Checking payment status... (2/60)"
   - dll setiap 5 detik
4. Bayar via Midtrans
5. Within 5-10 detik → auto redirect ke success
```

### Test 3: Manual Check
```
1. Klik button "Cek Status"
2. Should call /check-status API
3. Should return latest status from Midtrans
```

### Test 4: Max Attempts Reached
```
1. Buat payment (pending)
2. Don't pay
3. Wait 5 minutes (60 checks)
4. Should show notification: "Pembayaran masih pending..."
5. Auto-check stops
```

---

## 📊 Flow Comparison

### Before (Masalah):
```
Payment Created (pending)
    ↓
Redirect to Summary
    ↓
Auto-check every 3s (infinite) 🔄
    ├─ Call /check-status (NOT FOUND) ❌
    ├─ JavaScript error on button ❌
    └─ Never stops checking ∞
    
Result: Muter-muter terus, error, pending forever
```

### After (Fixed):
```
Payment Created (pending)
    ↓
Redirect to Summary
    ↓
Initial check after 2s ⏱️
    ↓
Auto-check every 5s (max 60x) 🔄
    ├─ Call /check-status ✅
    ├─ Call Midtrans API (real-time) 📡
    ├─ Update status if changed ✅
    └─ Redirect if completed ✅
    
If status = completed:
    → Redirect to Success Page ✅
    
If max attempts reached:
    → Stop checking
    → Show notification
```

---

## 🎯 Benefits

### User Experience:
- ✅ Faster response (2s initial check)
- ✅ No infinite loading
- ✅ Clear progress tracking
- ✅ Auto-redirect when paid
- ✅ Helpful notification if stuck

### Developer Experience:
- ✅ Better error handling
- ✅ Detailed logging
- ✅ Easy debugging
- ✅ No JavaScript errors

### Server Performance:
- ✅ Less frequent checks (5s vs 3s)
- ✅ Max 60 checks per session
- ✅ Proper cleanup

---

## 🔧 Troubleshooting

### Masih "Pending" Terus?

**Checklist**:
1. ✅ Midtrans webhook URL configured?
   - URL: `https://yoursite.com/midtrans/notification`
2. ✅ Server Key correct di `.env`?
3. ✅ Check Midtrans dashboard - payment status?
4. ✅ Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```
5. ✅ Manual test webhook:
   ```bash
   curl -X POST https://yoursite.com/midtrans/notification \
     -H "Content-Type: application/json" \
     -d '{
       "transaction_status": "settlement",
       "order_id": "ZKT-2025-1015",
       "transaction_id": "xxx"
     }'
   ```

### JavaScript Error Masih Muncul?

1. Clear browser cache: `Ctrl + Shift + R`
2. Check console untuk error details
3. Verify element IDs di HTML:
   ```html
   <button id="check-status">Cek Status</button>
   <button id="leave-page-button">Tutup</button>
   ```

### Auto-Check Tidak Jalan?

1. Check console log - ada error?
2. Check network tab - API calls success?
3. Verify route exists:
   ```bash
   php artisan route:list | grep check-status
   ```

---

## 📝 Related Files

### Modified:
1. `resources/views/payments/guest-summary.blade.php`
   - Fixed null check untuk event listeners
   - Improved auto-check logic
   - Better logging

2. `routes/web.php`
   - Added `/check-status` route
   - Added `/leave-page` route

3. `app/Http/Controllers/ZakatPaymentController.php`
   - Enhanced `guestCheckStatus()` method
   - Call Midtrans API for real-time status

### Already Exists (No Change):
1. `app/Observers/ZakatPaymentObserver.php` - Handles webhook updates
2. `app/Http/Controllers/ZakatPaymentController.php::handleNotification()` - Webhook handler

---

## 🚀 Next Steps

1. **Test di Production**: Test dengan payment real
2. **Monitor Logs**: Check `storage/logs/laravel.log` untuk errors
3. **Midtrans Dashboard**: Verify webhook URL configured
4. **User Feedback**: Collect feedback tentang loading time

---

**Fixed By:** AI Assistant  
**Date:** 29 Oktober 2025  
**Version:** 1.1.0  
**Status:** ✅ RESOLVED

