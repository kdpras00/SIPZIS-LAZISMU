<?php

namespace App\Observers;

use App\Models\ZakatPayment;
use App\Models\Campaign;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class ZakatPaymentObserver
{
    /**
     * Handle the ZakatPayment "creating" event.
     */
    public function creating(ZakatPayment $zakatPayment): void
    {
        // Validate that received_by can only be set by admin
        $this->validateReceivedBy($zakatPayment);
    }

    /**
     * Handle the ZakatPayment "updating" event.
     */
    public function updating(ZakatPayment $zakatPayment): void
    {
        // Validate that received_by can only be set by admin
        $this->validateReceivedBy($zakatPayment);
    }

    /**
     * Handle the ZakatPayment "created" event.
     */
    public function created(ZakatPayment $zakatPayment): void
    {
        // Update campaign collected amount when a new payment is created
        if ($zakatPayment->status === 'completed' && $zakatPayment->program_category) {
            $this->updateCampaignAndProgramTotals($zakatPayment);
        }

        // Send email notification to muzakki about payment status
        $this->sendPaymentEmail($zakatPayment);

        // Send WhatsApp notification
        $this->sendWhatsAppNotification($zakatPayment);
    }

    /**
     * Handle the ZakatPayment "updated" event.
     */
    public function updated(ZakatPayment $zakatPayment): void
    {
        // Update campaign collected amount when payment status changes to completed
        if ($zakatPayment->isDirty('status') && $zakatPayment->status === 'completed' && $zakatPayment->program_category) {
            $this->updateCampaignAndProgramTotals($zakatPayment);
        }

        // Send email notification when payment status changes
        if ($zakatPayment->isDirty('status')) {
            $this->sendPaymentEmail($zakatPayment);

            // Send WhatsApp notification
            $this->sendWhatsAppNotification($zakatPayment);
        }
    }

    /**
     * Handle the ZakatPayment "deleted" event.
     */
    public function deleted(ZakatPayment $zakatPayment): void
    {
        // Update campaign collected amount when a payment is deleted
        if ($zakatPayment->status === 'completed' && $zakatPayment->program_category) {
            $this->updateCampaignAndProgramTotals($zakatPayment);
        }
    }

    /**
     * Handle the ZakatPayment "restored" event.
     */
    public function restored(ZakatPayment $zakatPayment): void
    {
        // Update campaign collected amount when a payment is restored
        if ($zakatPayment->status === 'completed' && $zakatPayment->program_category) {
            $this->updateCampaignAndProgramTotals($zakatPayment);
        }
    }

    /**
     * Handle the ZakatPayment "force deleted" event.
     */
    public function forceDeleted(ZakatPayment $zakatPayment): void
    {
        // Update campaign collected amount when a payment is force deleted
        if ($zakatPayment->status === 'completed' && $zakatPayment->program_category) {
            $this->updateCampaignAndProgramTotals($zakatPayment);
        }
    }

    /**
     * Validate that received_by can only be set by admin users
     */
    private function validateReceivedBy(ZakatPayment $zakatPayment): void
    {
        // If received_by is being set, check that it references an admin user
        if ($zakatPayment->received_by !== null) {
            $user = User::find($zakatPayment->received_by);
            if ($user && $user->role !== 'admin') {
                // Reset to null if not admin
                $zakatPayment->received_by = null;
            }
        }
    }

    /**
     * Update campaign and program totals based on payment
     */
    private function updateCampaignAndProgramTotals(ZakatPayment $zakatPayment): void
    {
        // Find campaigns with the same program category
        $campaigns = Campaign::where('program_category', $zakatPayment->program_category)->get();

        // Update each campaign's collected amount
        foreach ($campaigns as $campaign) {
            // The collected amount is calculated dynamically in the model accessor
            // So we don't need to update the database field directly
            // But we can trigger any necessary recalculations here if needed
        }

        // Find program with the same category
        $program = Program::where('category', $zakatPayment->program_category)->first();

        if ($program) {
            // The program totals are calculated dynamically in the model accessor
            // So we don't need to update the database field directly
            // But we can trigger any necessary recalculations here if needed
        }
    }

    /**
     * Send email notification to muzakki about payment
     */
    private function sendPaymentEmail(ZakatPayment $zakatPayment): void
    {
        // Only send email if muzakki exists and has email
        if (!$zakatPayment->muzakki || !$zakatPayment->muzakki->email) {
            return;
        }

        try {
            // Send email only for completed, failed, and cancelled status
            // Pending status will NOT send email to avoid spam
            switch ($zakatPayment->status) {
                case 'completed':
                    // Send payment success email with receipt
                    Mail::to($zakatPayment->muzakki->email)
                        ->send(new \App\Mail\DonorPaymentStatus($zakatPayment, 'completed'));

                    // Also send payment confirmation
                    Mail::to($zakatPayment->muzakki->email)
                        ->send(new \App\Mail\DonorPaymentConfirmation($zakatPayment));

                    Log::info('Payment email sent to: ' . $zakatPayment->muzakki->email . ' for payment: ' . $zakatPayment->payment_code . ' with status: completed');
                    break;

                case 'pending':
                    // DO NOT send email for pending status
                    Log::info('Payment pending, no email sent to: ' . $zakatPayment->muzakki->email . ' for payment: ' . $zakatPayment->payment_code);
                    break;

                case 'failed':
                    // Send failed payment notification
                    Mail::to($zakatPayment->muzakki->email)
                        ->send(new \App\Mail\DonorPaymentStatus($zakatPayment, 'failed'));

                    Log::info('Payment email sent to: ' . $zakatPayment->muzakki->email . ' for payment: ' . $zakatPayment->payment_code . ' with status: failed');
                    break;

                case 'cancelled':
                    // Send cancelled payment notification
                    Mail::to($zakatPayment->muzakki->email)
                        ->send(new \App\Mail\DonorPaymentStatus($zakatPayment, 'cancelled'));

                    Log::info('Payment email sent to: ' . $zakatPayment->muzakki->email . ' for payment: ' . $zakatPayment->payment_code . ' with status: cancelled');
                    break;
            }
        } catch (\Exception $e) {
            // Log error but don't break the payment process
            Log::error('Failed to send payment email: ' . $e->getMessage() . ' for payment: ' . $zakatPayment->payment_code);
        }
    }

    /**
     * Send WhatsApp notification to muzakki about payment
     */
    private function sendWhatsAppNotification(ZakatPayment $zakatPayment): void
    {
        // Only send WhatsApp if muzakki exists
        if (!$zakatPayment->muzakki) {
            Log::info('No muzakki found for payment: ' . $zakatPayment->payment_code);
            return;
        }

        // Get phone number from muzakki
        $phone = $zakatPayment->muzakki->phone;

        // Skip if no phone number
        if (!$phone) {
            Log::info('No phone number for muzakki, skip WhatsApp notification for payment: ' . $zakatPayment->payment_code);
            return;
        }

        try {
            $whatsappService = new WhatsAppService();

            // Send WhatsApp based on payment status
            switch ($zakatPayment->status) {
                case 'completed':
                    $result = $whatsappService->sendPaymentSuccess($zakatPayment, $phone);
                    Log::channel('whatsapp')->info('Payment success WhatsApp sent', [
                        'payment_code' => $zakatPayment->payment_code,
                        'phone' => $phone,
                        'success' => $result['success']
                    ]);
                    break;

                case 'pending':
                    $result = $whatsappService->sendPaymentPending($zakatPayment, $phone);
                    Log::channel('whatsapp')->info('Payment pending WhatsApp sent', [
                        'payment_code' => $zakatPayment->payment_code,
                        'phone' => $phone,
                        'success' => $result['success']
                    ]);
                    break;

                case 'failed':
                    $result = $whatsappService->sendPaymentFailed($zakatPayment, $phone);
                    Log::channel('whatsapp')->info('Payment failed WhatsApp sent', [
                        'payment_code' => $zakatPayment->payment_code,
                        'phone' => $phone,
                        'success' => $result['success']
                    ]);
                    break;

                case 'cancelled':
                    $result = $whatsappService->sendPaymentCancelled($zakatPayment, $phone);
                    Log::channel('whatsapp')->info('Payment cancelled WhatsApp sent', [
                        'payment_code' => $zakatPayment->payment_code,
                        'phone' => $phone,
                        'success' => $result['success']
                    ]);
                    break;
            }
        } catch (\Exception $e) {
            // Log error but don't break the payment process
            Log::channel('whatsapp')->error('Failed to send WhatsApp notification', [
                'payment_code' => $zakatPayment->payment_code,
                'phone' => $phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
