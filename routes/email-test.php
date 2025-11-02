<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\DonorPaymentStatus;
use App\Mail\DonorPaymentConfirmation;
use App\Models\User;
use App\Models\ZakatPayment;

/*
|--------------------------------------------------------------------------
| Email Test Routes
|--------------------------------------------------------------------------
|
| Routes untuk testing email functionality
| PERHATIAN: Uncomment route yang ingin ditest
| 
| Cara menggunakan:
| 1. Uncomment route yang ingin ditest
| 2. Akses via browser: http://localhost:8000/test-welcome-email
| 3. Check email inbox/spam
| 4. Comment kembali setelah testing selesai
|
*/

// Test Basic Email
Route::get('/test-email', function () {
    try {
        Mail::raw('Ini percobaan kirim email via Gmail SMTP.', function ($message) {
            $message->to('kdpras00@gmail.com')
                ->subject('Test Email Laravel Gmail');
        });
        return response()->json([
            'success' => true,
            'message' => 'Email percobaan sudah dikirim! Cek inbox/spam.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test Welcome Email
Route::get('/test-welcome-email', function () {
    try {
        $user = User::where('role', 'muzakki')->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No muzakki user found in database!'
            ], 404);
        }

        Mail::to($user->email)->send(new WelcomeMail($user));
        
        return response()->json([
            'success' => true,
            'message' => 'Welcome email sent to: ' . $user->email,
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ],
            'note' => 'Check inbox/spam folder!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test Payment Email - Pending
Route::get('/test-payment-pending', function () {
    try {
        $payment = ZakatPayment::with('muzakki')
            ->whereHas('muzakki', function($query) {
                $query->whereNotNull('email');
            })
            ->first();
        
        if (!$payment || !$payment->muzakki) {
            return response()->json([
                'success' => false,
                'message' => 'No payment with muzakki found!'
            ], 404);
        }

        Mail::to($payment->muzakki->email)
            ->send(new DonorPaymentStatus($payment, 'pending'));
        
        return response()->json([
            'success' => true,
            'message' => 'Pending payment email sent!',
            'recipient' => $payment->muzakki->email,
            'payment' => [
                'code' => $payment->payment_code,
                'amount' => 'Rp ' . number_format($payment->paid_amount, 0, ',', '.'),
                'status' => 'pending'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test Payment Email - Completed
Route::get('/test-payment-completed', function () {
    try {
        $payment = ZakatPayment::with('muzakki')
            ->whereHas('muzakki', function($query) {
                $query->whereNotNull('email');
            })
            ->first();
        
        if (!$payment || !$payment->muzakki) {
            return response()->json([
                'success' => false,
                'message' => 'No payment with muzakki found!'
            ], 404);
        }

        // Send 2 emails for completed payment
        Mail::to($payment->muzakki->email)
            ->send(new DonorPaymentStatus($payment, 'completed'));
            
        Mail::to($payment->muzakki->email)
            ->send(new DonorPaymentConfirmation($payment));
        
        return response()->json([
            'success' => true,
            'message' => '2 emails sent for completed payment!',
            'recipient' => $payment->muzakki->email,
            'emails' => [
                '1. Payment Status (Completed)',
                '2. Payment Confirmation'
            ],
            'payment' => [
                'code' => $payment->payment_code,
                'amount' => 'Rp ' . number_format($payment->paid_amount, 0, ',', '.'),
                'status' => 'completed'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test Payment Email - Failed
Route::get('/test-payment-failed', function () {
    try {
        $payment = ZakatPayment::with('muzakki')
            ->whereHas('muzakki', function($query) {
                $query->whereNotNull('email');
            })
            ->first();
        
        if (!$payment || !$payment->muzakki) {
            return response()->json([
                'success' => false,
                'message' => 'No payment with muzakki found!'
            ], 404);
        }

        Mail::to($payment->muzakki->email)
            ->send(new DonorPaymentStatus($payment, 'failed'));
        
        return response()->json([
            'success' => true,
            'message' => 'Failed payment email sent!',
            'recipient' => $payment->muzakki->email,
            'payment' => [
                'code' => $payment->payment_code,
                'amount' => 'Rp ' . number_format($payment->paid_amount, 0, ',', '.'),
                'status' => 'failed'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test All Payment Emails
Route::get('/test-all-payment-emails', function () {
    try {
        $payment = ZakatPayment::with('muzakki')
            ->whereHas('muzakki', function($query) {
                $query->whereNotNull('email');
            })
            ->first();
        
        if (!$payment || !$payment->muzakki) {
            return response()->json([
                'success' => false,
                'message' => 'No payment with muzakki found!'
            ], 404);
        }

        $results = [];
        
        // Test Pending
        Mail::to($payment->muzakki->email)
            ->send(new DonorPaymentStatus($payment, 'pending'));
        $results[] = 'Pending email sent';
        
        // Test Completed + Confirmation
        Mail::to($payment->muzakki->email)
            ->send(new DonorPaymentStatus($payment, 'completed'));
        $results[] = 'Completed email sent';
        
        Mail::to($payment->muzakki->email)
            ->send(new DonorPaymentConfirmation($payment));
        $results[] = 'Confirmation email sent';
        
        // Test Failed
        Mail::to($payment->muzakki->email)
            ->send(new DonorPaymentStatus($payment, 'failed'));
        $results[] = 'Failed email sent';
        
        return response()->json([
            'success' => true,
            'message' => 'All payment emails sent!',
            'recipient' => $payment->muzakki->email,
            'total_emails' => count($results),
            'emails_sent' => $results,
            'note' => 'Check inbox/spam folder!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Check Email Config
Route::get('/test-email-config', function () {
    return response()->json([
        'mail_driver' => config('mail.default'),
        'mail_host' => config('mail.mailers.smtp.host'),
        'mail_port' => config('mail.mailers.smtp.port'),
        'mail_username' => config('mail.mailers.smtp.username'),
        'mail_from_address' => config('mail.from.address'),
        'mail_from_name' => config('mail.from.name'),
        'note' => 'Password hidden for security'
    ]);
});

