<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ZakatPayment;

class WhatsAppService
{
    protected $apiUrl;
    protected $token;
    protected $enabled;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->token = config('services.whatsapp.token');
        $this->enabled = config('services.whatsapp.enabled', false);
    }

    /**
     * Send WhatsApp message using Fonnte API
     * 
     * @param string $phone Phone number in format 628xxx
     * @param string $message Message content
     * @return array Response from API
     */
    public function sendMessage($phone, $message)
    {
        // Check if WhatsApp is enabled
        if (!$this->enabled) {
            Log::info('WhatsApp is disabled in config', ['phone' => $phone]);
            return [
                'success' => false,
                'message' => 'WhatsApp notification is disabled'
            ];
        }

        // Validate token
        if (empty($this->token)) {
            Log::error('WhatsApp API token not configured');
            return [
                'success' => false,
                'message' => 'WhatsApp API token not configured'
            ];
        }

        // Format phone number
        $formattedPhone = $this->formatPhoneNumber($phone);
        
        if (!$formattedPhone) {
            Log::error('Invalid phone number format', ['phone' => $phone]);
            return [
                'success' => false,
                'message' => 'Invalid phone number format'
            ];
        }

        try {
            // Send message using Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62', // Indonesia
            ]);

            $result = $response->json();

            // Log the response
            Log::channel('whatsapp')->info('WhatsApp message sent', [
                'phone' => $formattedPhone,
                'status' => $response->status(),
                'response' => $result,
                'sent_at' => now(),
            ]);

            return [
                'success' => $response->successful(),
                'message' => $result['message'] ?? 'Message sent',
                'response' => $result
            ];

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Failed to send WhatsApp message', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to 62xxx format
     * 
     * @param string $phone
     * @return string|null
     */
    protected function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 08xxx to 628xxx
        if (substr($phone, 0, 2) === '08') {
            $phone = '62' . substr($phone, 1);
        }

        // Ensure it starts with 62
        if (substr($phone, 0, 2) !== '62') {
            return null;
        }

        // Validate length (Indonesian phone numbers)
        if (strlen($phone) < 10 || strlen($phone) > 15) {
            return null;
        }

        return $phone;
    }

    /**
     * Send payment pending notification
     */
    public function sendPaymentPending(ZakatPayment $payment, $phone)
    {
        $programName = $this->getProgramName($payment);
        $amount = number_format($payment->paid_amount, 0, ',', '.');
        $trackingUrl = route('guest.payment.summary', $payment->payment_code);

        $message = "🕐 *DONASI PENDING*\n\n";
        $message .= "Halo *{$payment->muzakki->name}*,\n\n";
        $message .= "Terima kasih telah berdonasi melalui SIPZIS!\n\n";
        $message .= "📋 *Detail Donasi:*\n";
        $message .= "• Kode: {$payment->payment_code}\n";
        $message .= "• Program: {$programName}\n";
        $message .= "• Nominal: Rp {$amount}\n";
        $message .= "• Status: ⏳ Menunggu Pembayaran\n\n";
        $message .= "💳 Silakan selesaikan pembayaran Anda.\n\n";
        $message .= "Cek status: {$trackingUrl}\n\n";
        $message .= "_SIPZIS - Sistem Informasi Pengelolaan Zakat_";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send payment success notification
     */
    public function sendPaymentSuccess(ZakatPayment $payment, $phone)
    {
        $programName = $this->getProgramName($payment);
        $amount = number_format($payment->paid_amount, 0, ',', '.');
        $date = $payment->payment_date ? $payment->payment_date->format('d M Y H:i') : now()->format('d M Y H:i');

        $message = "✅ *DONASI BERHASIL*\n\n";
        $message .= "Alhamdulillah! 🎉\n\n";
        $message .= "Halo *{$payment->muzakki->name}*,\n\n";
        $message .= "Donasi Anda telah berhasil diterima.\n\n";
        $message .= "📋 *Detail Donasi:*\n";
        $message .= "• Kode: {$payment->payment_code}\n";
        $message .= "• Program: {$programName}\n";
        $message .= "• Nominal: Rp {$amount}\n";
        $message .= "• Tanggal: {$date}\n\n";
        $message .= "Jazakallahu khairan katsiran! 🤲\n\n";
        $message .= "Bukti donasi telah dikirim ke email Anda.\n\n";
        $message .= "_SIPZIS - Sistem Informasi Pengelolaan Zakat_";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send payment failed notification
     */
    public function sendPaymentFailed(ZakatPayment $payment, $phone)
    {
        $amount = number_format($payment->paid_amount, 0, ',', '.');
        $retryUrl = route('guest.payment.create', [
            'program_id' => $payment->program_id,
            'category' => $payment->program_category,
            'amount' => $payment->paid_amount
        ]);

        $message = "❌ *DONASI GAGAL*\n\n";
        $message .= "Halo *{$payment->muzakki->name}*,\n\n";
        $message .= "Maaf, pembayaran Anda gagal diproses.\n\n";
        $message .= "📋 *Detail:*\n";
        $message .= "• Kode: {$payment->payment_code}\n";
        $message .= "• Nominal: Rp {$amount}\n\n";
        $message .= "Silakan coba lagi atau hubungi kami.\n\n";
        $message .= "🔄 Donasi Ulang: {$retryUrl}\n";
        $message .= "📞 Bantuan: admin@sipzis.com\n\n";
        $message .= "_SIPZIS - Sistem Informasi Pengelolaan Zakat_";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send payment cancelled notification
     */
    public function sendPaymentCancelled(ZakatPayment $payment, $phone)
    {
        $amount = number_format($payment->paid_amount, 0, ',', '.');
        $retryUrl = route('guest.payment.create', [
            'program_id' => $payment->program_id,
            'category' => $payment->program_category
        ]);

        $message = "🚫 *DONASI DIBATALKAN*\n\n";
        $message .= "Halo *{$payment->muzakki->name}*,\n\n";
        $message .= "Pembayaran Anda telah dibatalkan.\n\n";
        $message .= "📋 *Detail:*\n";
        $message .= "• Kode: {$payment->payment_code}\n";
        $message .= "• Nominal: Rp {$amount}\n\n";
        $message .= "Ingin berdonasi lagi?\n";
        $message .= "🔄 Donasi Baru: {$retryUrl}\n\n";
        $message .= "_SIPZIS - Sistem Informasi Pengelolaan Zakat_";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send welcome message for new donor
     */
    public function sendWelcomeMessage($muzakki)
    {
        if (!$muzakki->phone) {
            return [
                'success' => false,
                'message' => 'No phone number provided'
            ];
        }

        $message = "👋 *SELAMAT DATANG DI SIPZIS*\n\n";
        $message .= "Halo *{$muzakki->name}*!\n\n";
        $message .= "Terima kasih telah bergabung dengan Sistem Informasi Pengelolaan Zakat (SIPZIS).\n\n";
        $message .= "Bersama kita wujudkan:\n";
        $message .= "✅ Transparansi pengelolaan zakat\n";
        $message .= "✅ Kemudahan berdonasi\n";
        $message .= "✅ Penyaluran tepat sasaran\n\n";
        $message .= "Mari mulai berbagi kebaikan! 💚\n\n";
        $message .= "_SIPZIS - Sistem Informasi Pengelolaan Zakat_";

        return $this->sendMessage($muzakki->phone, $message);
    }

    /**
     * Get program name from payment
     */
    protected function getProgramName(ZakatPayment $payment)
    {
        if ($payment->program) {
            return $payment->program->name;
        }

        if ($payment->campaign) {
            return $payment->campaign->title;
        }

        if ($payment->program_category) {
            $categoryNames = [
                'pendidikan' => 'Donasi Pendidikan',
                'kesehatan' => 'Donasi Kesehatan',
                'ekonomi' => 'Donasi Ekonomi',
                'sosial-dakwah' => 'Donasi Sosial Dakwah',
                'kemanusiaan' => 'Donasi Kemanusiaan',
                'lingkungan' => 'Donasi Lingkungan',
                'zakat-mal' => 'Zakat Mal',
                'zakat-fitrah' => 'Zakat Fitrah',
                'zakat-profesi' => 'Zakat Profesi',
                'infaq-masjid' => 'Infaq Masjid',
                'shadaqah-jariyah' => 'Shadaqah Jariyah',
            ];

            return $categoryNames[$payment->program_category] ?? 'Donasi Umum';
        }

        return 'Donasi Umum';
    }

}

