<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\ZakatPayment;

class DonorPaymentStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $donorName;
    public $status;
    public $statusMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ZakatPayment $payment, $status)
    {
        $this->payment = $payment;
        $this->donorName = $payment->muzakki->name ?? 'Hamba Allah';
        $this->status = $status;

        // Set status message based on status
        switch ($status) {
            case 'pending':
                $this->statusMessage = 'Menunggu Konfirmasi';
                break;
            case 'completed':
                $this->statusMessage = 'Pembayaran Berhasil';
                break;
            case 'failed':
                $this->statusMessage = 'Pembayaran Gagal';
                break;
            case 'cancelled':
                $this->statusMessage = 'Pembayaran Dibatalkan';
                break;
            default:
                $this->statusMessage = 'Status Pembayaran';
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject('Status Pembayaran Donasi Anda - ' . $this->statusMessage)
            ->view('emails.donor.payment-status')
            ->with([
                'payment' => $this->payment,
                'donorName' => $this->donorName,
                'status' => $this->status,
                'statusMessage' => $this->statusMessage,
            ]);

        // Attach PDF receipt if payment is completed
        if ($this->status === 'completed' && $this->payment->status === 'completed') {
            try {
                // Ensure payment relationships are loaded
                if (!$this->payment->relationLoaded('muzakki')) {
                    $this->payment->load('muzakki');
                }
                if (!$this->payment->relationLoaded('programType')) {
                    $this->payment->load('programType');
                }
                
                // Generate PDF receipt
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.guest-receipt-pdf', [
                    'payment' => $this->payment
                ]);
                
                $pdf->setPaper('A4');
                
                // Generate PDF content as string (in-memory) instead of saving to file
                // This avoids file cleanup issues and is more efficient
                $pdfContent = $pdf->output();
                
                // Validate PDF content was generated
                if (empty($pdfContent)) {
                    throw new \Exception('PDF content is empty');
                }
                
                // Attach PDF to email directly from memory
                $email->attachData($pdfContent, 'kwitansi-' . $this->payment->payment_code . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
                
                Log::info('PDF receipt attached to email successfully', [
                    'payment_code' => $this->payment->payment_code,
                    'email' => $this->payment->muzakki->email ?? 'N/A',
                    'pdf_size' => strlen($pdfContent)
                ]);
                
            } catch (\Exception $e) {
                // Log detailed error information
                Log::error('Failed to attach PDF receipt to email', [
                    'payment_code' => $this->payment->payment_code,
                    'email' => $this->payment->muzakki->email ?? 'N/A',
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Continue sending email even if PDF attachment fails
                // This ensures the user still receives the payment confirmation email
            }
        }

        return $email;
    }
}
