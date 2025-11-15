<?php

namespace App\Observers;

use App\Models\ZakatPayment;
use App\Models\Campaign;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
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

        // Send notifications based on payment status
        // For pending status: only send WhatsApp (no email to avoid spam)
        // For other statuses: send both email and WhatsApp
        if ($zakatPayment->status === 'pending') {
            // Send WhatsApp notification for pending payments
            $this->sendWhatsAppNotification($zakatPayment);
        } else {
            // Send both email and WhatsApp for non-pending statuses
            $this->sendPaymentEmail($zakatPayment);
            $this->sendWhatsAppNotification($zakatPayment);
        }
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

        // Send notifications when payment status changes
        if ($zakatPayment->isDirty('status')) {
            // Get the original status before update
            $originalStatus = $zakatPayment->getOriginal('status');
            $newStatus = $zakatPayment->status;

            // Only send notifications if status actually changed
            if ($originalStatus !== $newStatus) {
                // For pending status: only send WhatsApp (no email to avoid spam)
                // For other statuses: send both email and WhatsApp
                if ($newStatus === 'pending') {
                    // Send WhatsApp notification for pending payments
                    $this->sendWhatsAppNotification($zakatPayment);
                } else {
                    // Send both email and WhatsApp for non-pending statuses
                    $this->sendPaymentEmail($zakatPayment);
                    $this->sendWhatsAppNotification($zakatPayment);
                }
            }
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
        // Find campaigns that should be updated based on payment
        $campaigns = collect();

        // If payment has program_id, find campaigns with that program_id
        if ($zakatPayment->program_id) {
            $campaigns = Campaign::where('program_id', $zakatPayment->program_id)
                ->get();
        }

        // Also find campaigns with same program_category (for backward compatibility)
        // Only if payment was created after campaign was created
        if ($zakatPayment->program_category) {
            $campaignsByCategory = Campaign::where('program_category', $zakatPayment->program_category)
                ->where('created_at', '<=', $zakatPayment->created_at)
                ->get();
            $campaigns = $campaigns->merge($campaignsByCategory)->unique('id');
        }

        // Update each campaign's collected amount in database
        foreach ($campaigns as $campaign) {
            $collectedAmount = 0;

            // Calculate collected amount based on campaign's program_id or program_category
            if ($campaign->program_id) {
                $collectedAmount = \App\Models\ZakatPayment::where('program_id', $campaign->program_id)
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $campaign->created_at)
                    ->sum('paid_amount');
            } else {
                $collectedAmount = \App\Models\ZakatPayment::where('program_category', $campaign->program_category)
                    ->whereNotNull('program_category')
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $campaign->created_at)
                    ->sum('paid_amount');
            }

            // Update collected_amount in database
            $campaign->update(['collected_amount' => $collectedAmount]);
        }

        // Update program totals (programs use accessor, so no need to update database)
        // But we can clear cache if needed
        if ($zakatPayment->program_id) {
            $program = Program::find($zakatPayment->program_id);
            if ($program) {
                Cache::forget("program_total_collected_{$program->id}");
            }
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

        // Create cache key to prevent duplicate email sending
        $cacheKey = "email_sent_{$zakatPayment->payment_code}_{$zakatPayment->status}";

        // Check if email already sent for this payment_code + status combination
        if (Cache::has($cacheKey)) {
            Log::info('Email already sent, skipping duplicate: ' . $zakatPayment->payment_code . ' with status: ' . $zakatPayment->status);
            return;
        }

        try {
            // Send email only for completed, failed, and cancelled status
            // Pending status will NOT send email to avoid spam
            switch ($zakatPayment->status) {
                case 'completed':
                    // Send payment success email with receipt (only one email to avoid duplicate)
                    // DonorPaymentStatus already contains all necessary information
                    try {
                        Mail::to($zakatPayment->muzakki->email)
                            ->send(new \App\Mail\DonorPaymentStatus($zakatPayment, 'completed'));

                        // Set cache to prevent duplicate email (expires in 24 hours)
                        Cache::put($cacheKey, true, now()->addHours(24));

                        Log::info('Payment email sent successfully', [
                            'email' => $zakatPayment->muzakki->email,
                            'payment_code' => $zakatPayment->payment_code,
                            'status' => 'completed'
                        ]);
                    } catch (\Exception $mailException) {
                        // Log detailed error but don't break the payment process
                        Log::error('Failed to send payment email', [
                            'email' => $zakatPayment->muzakki->email,
                            'payment_code' => $zakatPayment->payment_code,
                            'status' => 'completed',
                            'error_message' => $mailException->getMessage(),
                            'error_file' => $mailException->getFile(),
                            'error_line' => $mailException->getLine(),
                            'trace' => $mailException->getTraceAsString()
                        ]);
                        // Don't set cache on error so it can be retried
                    }
                    break;

                case 'pending':
                    // DO NOT send email for pending status
                    Log::info('Payment pending, no email sent', [
                        'email' => $zakatPayment->muzakki->email,
                        'payment_code' => $zakatPayment->payment_code
                    ]);
                    break;

                case 'failed':
                    // Send failed payment notification
                    try {
                        Mail::to($zakatPayment->muzakki->email)
                            ->send(new \App\Mail\DonorPaymentStatus($zakatPayment, 'failed'));

                        // Set cache to prevent duplicate email (expires in 24 hours)
                        Cache::put($cacheKey, true, now()->addHours(24));

                        Log::info('Payment email sent successfully', [
                            'email' => $zakatPayment->muzakki->email,
                            'payment_code' => $zakatPayment->payment_code,
                            'status' => 'failed'
                        ]);
                    } catch (\Exception $mailException) {
                        Log::error('Failed to send payment email', [
                            'email' => $zakatPayment->muzakki->email,
                            'payment_code' => $zakatPayment->payment_code,
                            'status' => 'failed',
                            'error_message' => $mailException->getMessage(),
                            'error_file' => $mailException->getFile(),
                            'error_line' => $mailException->getLine(),
                            'trace' => $mailException->getTraceAsString()
                        ]);
                    }
                    break;

                case 'cancelled':
                    // Send cancelled payment notification
                    try {
                        Mail::to($zakatPayment->muzakki->email)
                            ->send(new \App\Mail\DonorPaymentStatus($zakatPayment, 'cancelled'));

                        // Set cache to prevent duplicate email (expires in 24 hours)
                        Cache::put($cacheKey, true, now()->addHours(24));

                        Log::info('Payment email sent successfully', [
                            'email' => $zakatPayment->muzakki->email,
                            'payment_code' => $zakatPayment->payment_code,
                            'status' => 'cancelled'
                        ]);
                    } catch (\Exception $mailException) {
                        Log::error('Failed to send payment email', [
                            'email' => $zakatPayment->muzakki->email,
                            'payment_code' => $zakatPayment->payment_code,
                            'status' => 'cancelled',
                            'error_message' => $mailException->getMessage(),
                            'error_file' => $mailException->getFile(),
                            'error_line' => $mailException->getLine(),
                            'trace' => $mailException->getTraceAsString()
                        ]);
                    }
                    break;
            }
        } catch (\Exception $e) {
            // Log error but don't break the payment process
            Log::error('Failed to send payment email - outer exception', [
                'payment_code' => $zakatPayment->payment_code,
                'email' => $zakatPayment->muzakki->email ?? 'N/A',
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
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
