<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Muzakki;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class OTPController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    // Kirim kode OTP ke nomor WA
    public function sendOTP(Request $request)
    {
        try {
            Log::info('sendOTP request received', ['input' => $request->all()]);

            // Handle both +62 and 62 formats
            $phone = $request->input('phone');
            if (substr($phone, 0, 1) === '+') {
                $phone = substr($phone, 1);
            }

            // Validate the processed phone number
            $request->merge(['phone' => $phone]);
            $validated = $request->validate([
                'phone' => ['required', 'regex:/^62\d{8,15}$/'], // format: 628xxxx
            ]);

            Log::info('sendOTP phone validation passed', ['phone' => $phone]);

            $otp = rand(1000, 9999);

            // Simpan ke session
            Session::put('otp_code', $otp);
            Session::put('otp_phone', $phone);
            Session::put('otp_expires', now()->addMinutes(5));

            Log::info('sendOTP session data stored', [
                'otp_code' => $otp,
                'otp_phone' => $phone,
                'otp_expires' => Session::get('otp_expires')
            ]);

            // Kirim via WhatsAppService (Fonnte)
            $message = "Kode OTP Anda adalah *{$otp}*. Berlaku 5 menit.";
            $result = $this->whatsAppService->sendMessage($phone, $message);

            Log::info('sendOTP WhatsApp result', [
                'success' => $result['success'],
                'message' => $result['message'] ?? null,
                'response' => $result['response'] ?? null
            ]);

            if ($result['success']) {
                // TIDAK PERLU ALERT - Toast akan handle di frontend
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP telah dikirim ke WhatsApp Anda.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal mengirim OTP. Silakan coba lagi.'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('sendOTP validation error', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Format nomor telepon tidak valid. Pastikan nomor diawali dengan 62 diikuti 8-15 digit angka (contoh: 6281234567890)'
            ], 422);
        } catch (\Exception $e) {
            Log::error('sendOTP error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim OTP. Silakan coba lagi.'
            ], 500);
        }
    }

    // Verifikasi kode OTP
    public function verifyOTP(Request $request)
    {
        try {
            Log::info('verifyOTP request received', ['input' => $request->all()]);

            $validated = $request->validate(['otp' => 'required|digits:4']);

            Log::info('verifyOTP validation passed', ['otp' => $validated['otp']]);

            if (!Session::has('otp_code') || !Session::has('otp_phone')) {
                Log::warning('verifyOTP session data missing');
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP tidak ditemukan atau sudah kadaluarsa.'
                ]);
            }

            Log::info('verifyOTP session data found', [
                'otp_code' => Session::get('otp_code'),
                'otp_phone' => Session::get('otp_phone'),
                'otp_expires' => Session::get('otp_expires')
            ]);

            // Check expiration
            if (now()->greaterThan(Session::get('otp_expires'))) {
                Session::forget(['otp_code', 'otp_phone', 'otp_expires']);
                Log::info('verifyOTP OTP expired');
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode.'
                ]);
            }

            // Verify OTP code
            if ($request->otp == Session::get('otp_code')) {
                $otpPhone = Session::get('otp_phone');

                // Clear OTP session
                Session::forget(['otp_code', 'otp_phone', 'otp_expires']);
                Log::info('verifyOTP successful');

                // Update muzakki record to mark phone as verified
                if (Auth::check()) {
                    $muzakki = Auth::user()->muzakki;
                    if ($muzakki) {
                        // Normalize phone numbers for comparison
                        $normalizedMuzakkiPhone = preg_replace('/^\+/', '', $muzakki->phone);
                        $normalizedOtpPhone = preg_replace('/^\+/', '', $otpPhone);

                        if ($normalizedMuzakkiPhone === $normalizedOtpPhone) {
                            $muzakki->phone_verified = true;
                            $muzakki->save();
                            Log::info('verifyOTP muzakki phone_verified updated', ['muzakki_id' => $muzakki->id]);
                        } else {
                            Log::warning('verifyOTP phone mismatch', [
                                'muzakki_phone' => $normalizedMuzakkiPhone,
                                'otp_phone' => $normalizedOtpPhone
                            ]);
                        }
                    }
                }

                // TIDAK PERLU ALERT - Toast akan handle di frontend
                return response()->json([
                    'success' => true,
                    'message' => 'Nomor WhatsApp berhasil diverifikasi.',
                    'verified' => true
                ]);
            } else {
                Log::info('verifyOTP code mismatch', [
                    'input_otp' => $request->otp,
                    'session_otp' => Session::get('otp_code')
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP salah. Silakan coba lagi.'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('verifyOTP validation error', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP harus terdiri dari 4 digit angka.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('verifyOTP error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi OTP. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Resend OTP (optional - untuk handling resend yang lebih baik)
     */
    public function resendOTP(Request $request)
    {
        try {
            // Check if there's an active OTP session
            if (!Session::has('otp_phone')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada permintaan OTP yang aktif.'
                ], 400);
            }

            $phone = Session::get('otp_phone');

            // Generate new OTP
            $otp = rand(1000, 9999);

            // Update session
            Session::put('otp_code', $otp);
            Session::put('otp_expires', now()->addMinutes(5));

            Log::info('resendOTP new code generated', [
                'phone' => $phone,
                'otp' => $otp
            ]);

            // Send via WhatsAppService (Fonnte)
            $message = "Kode OTP Anda adalah *{$otp}*. Berlaku 5 menit.";
            $result = $this->whatsAppService->sendMessage($phone, $message);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP baru telah dikirim.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal mengirim ulang OTP. Silakan coba lagi.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('resendOTP error', [
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }
}
