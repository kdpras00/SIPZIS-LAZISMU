<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        $message .= "Setelah pembayaran berhasil, kwitansi akan dikirim ke email Anda.\n\n";
        $message .= "_SIPZIS - Sistem Informasi Pengelolaan Zakat_";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send payment success notification with PDF receipt
     */
    public function sendPaymentSuccess(ZakatPayment $payment, $phone)
    {
        $programName = $this->getProgramName($payment);
        $amount = number_format($payment->paid_amount, 0, ',', '.');

        // Use updated_at for completed payments to get accurate date and time when payment was completed
        // This ensures we show the actual date and time when the payment status changed to completed
        if ($payment->updated_at && $payment->status === 'completed') {
            // Use updated_at when status is completed (shows when payment was actually completed)
            $dateTime = $payment->updated_at;
        } elseif ($payment->created_at) {
            // Fallback to created_at if updated_at not available
            $dateTime = $payment->created_at;
        } else {
            // Last fallback to current time
            $dateTime = now();
        }

        // Convert to Carbon if not already
        if (!$dateTime instanceof \Carbon\Carbon) {
            $dateTime = \Carbon\Carbon::parse($dateTime);
        }

        // Format with timezone Indonesia (WIB) - shows actual date and time
        $date = $dateTime->setTimezone('Asia/Jakarta')->format('d M Y H:i');

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
        $message .= "📄 Kwitansi pembayaran dilampirkan pada pesan ini.\n";
        $message .= "Kwitansi juga telah dikirim ke email Anda dalam format PDF.\n\n";
        $message .= "_SIPZIS - Sistem Informasi Pengelolaan Zakat_";

        // Send message with PDF receipt in one go
        return $this->sendReceiptPDF($payment, $phone, $message);
    }

    /**
     * Send PDF receipt as document via WhatsApp
     *
     * @param ZakatPayment $payment
     * @param string $phone
     * @param string|null $customMessage Custom message to send with PDF. If null, uses default message.
     * @return array
     */
    public function sendReceiptPDF(ZakatPayment $payment, $phone, $customMessage = null)
    {
        // Check if WhatsApp is enabled
        if (!$this->enabled) {
            return [
                'success' => false,
                'message' => 'WhatsApp is disabled in config'
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
            return [
                'success' => false,
                'message' => 'Invalid phone number format'
            ];
        }

        try {
            // Generate PDF receipt
            $payment->load(['muzakki', 'programType']);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.guest-receipt-pdf', [
                'payment' => $payment
            ]);
            $pdf->setPaper('A4');

            // Generate PDF content
            $pdfContent = $pdf->output();

            // Save PDF to public storage for backup/reference
            $filename = 'kwitansi-' . $payment->payment_code . '.pdf';
            $storagePath = 'receipts/' . $filename;
            Storage::disk('public')->put($storagePath, $pdfContent);

            // Use custom message if provided, otherwise use default
            $message = $customMessage ?? 'Kwitansi Pembayaran Donasi Anda';

            // Get the full public URL for the PDF
            // Ensure storage symlink exists: php artisan storage:link
            $publicUrl = asset('storage/' . $storagePath);

            // If using localhost/development, use APP_URL for full URL
            $appUrl = config('app.url');
            if (str_contains($publicUrl, 'localhost') || str_contains($publicUrl, '127.0.0.1')) {
                // For local development, use the configured APP_URL
                $publicUrl = rtrim($appUrl, '/') . '/storage/' . $storagePath;
            }

            // Try method 1: Using URL (works if URL is publicly accessible)
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $formattedPhone,
                'message' => $message,
                'type' => 'document',
                'document' => $publicUrl,
                'filename' => $filename,
            ]);

            $result = $response->json();

            // If URL method fails (likely because URL is not publicly accessible),
            // try base64 method as fallback
            if (!$response->successful()) {
                Log::channel('whatsapp')->warning('URL method failed, trying base64 method', [
                    'error' => $result['message'] ?? 'Unknown error',
                    'payment_code' => $payment->payment_code,
                    'url_tried' => $publicUrl,
                ]);

                // Convert PDF to base64 for Fonnte API
                $base64Pdf = base64_encode($pdfContent);

                $response = Http::withHeaders([
                    'Authorization' => $this->token,
                ])->post($this->apiUrl, [
                    'target' => $formattedPhone,
                    'message' => $message,
                    'type' => 'document',
                    'document' => $base64Pdf,
                    'filename' => $filename,
                ]);
            }

            $result = $response->json();

            Log::channel('whatsapp')->info('WhatsApp PDF receipt sent', [
                'phone' => $formattedPhone,
                'payment_code' => $payment->payment_code,
                'status' => $response->status(),
                'response' => $result,
            ]);

            return [
                'success' => $response->successful(),
                'message' => $result['message'] ?? 'PDF sent',
                'response' => $result
            ];
        } catch (\Exception $e) {
            // Clean up file from storage on error if it was created
            if (isset($storagePath)) {
                try {
                    Storage::disk('public')->delete($storagePath);
                } catch (\Exception $deleteException) {
                    // Ignore cleanup errors
                }
            }

            Log::channel('whatsapp')->error('Failed to send WhatsApp PDF receipt', [
                'phone' => $formattedPhone,
                'payment_code' => $payment->payment_code,
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
