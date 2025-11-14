<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZakatPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'midtrans_order_id',
        'snap_token',
        'muzakki_id',
        'program_id',
        'program_category',
        'program_type_id',
        'zakat_type_id',
        'zakat_amount',
        'paid_amount',
        'payment_method',
        'midtrans_payment_method',
        'payment_reference',
        'payment_date',
        'notes',
        'status',
        'receipt_number',
        'received_by',
        'is_guest_payment'
    ];

    protected $casts = [
        'zakat_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    // Relationships
    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class);
    }

    public function zakatType()
    {
        return $this->belongsTo(ZakatType::class, 'zakat_type_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function programType()
    {
        return $this->belongsTo(ProgramType::class, 'program_type_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // Methods
    public static function generatePaymentCode()
    {
        $year = date('Y');

        // Try up to 10 times to generate a unique code
        for ($i = 0; $i < 10; $i++) {
            // Generate base code
            $lastPayment = self::where('payment_code', 'like', "DNS-{$year}-%")
                ->orderBy('id', 'desc')
                ->first();

            if ($lastPayment) {
                // Extract the number part and increment it
                $lastCode = $lastPayment->payment_code;
                $parts = explode('-', $lastCode);
                if (count($parts) >= 3) {
                    $lastNumber = (int) $parts[2];
                    $newNumber = $lastNumber + 1;
                } else {
                    // Fallback if format is unexpected
                    $lastNumber = (int) substr($lastCode, -3);
                    $newNumber = $lastNumber + 1;
                }
            } else {
                $newNumber = 1;
            }

            // Add a small random component to reduce collision probability
            if ($i > 0) {
                $newNumber = $newNumber * 10 + rand(0, 9);
            }

            // Ensure we don't exceed reasonable limits
            $newNumber = $newNumber % 10000;

            $paymentCode = "DNS-{$year}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // Check if this code already exists
            if (!self::where('payment_code', $paymentCode)->exists()) {
                return $paymentCode;
            }
        }

        // If we still can't generate a unique code, use timestamp
        return "DNS-{$year}-" . substr(time(), -6);
    }

    public static function generateReceiptNumber()
    {
        $year = date('Y');
        $month = date('m');

        // Try up to 10 times to generate a unique receipt number
        for ($i = 0; $i < 10; $i++) {
            $lastReceipt = self::where('receipt_number', 'like', "RCP-{$year}{$month}-%")
                ->orderBy('id', 'desc')
                ->first();

            if ($lastReceipt) {
                $lastCode = $lastReceipt->receipt_number;
                $parts = explode('-', $lastCode);
                if (count($parts) >= 3) {
                    $lastNumber = (int) $parts[2];
                    $newNumber = $lastNumber + 1;
                } else {
                    // Fallback if format is unexpected
                    $lastNumber = (int) substr($lastCode, -4);
                    $newNumber = $lastNumber + 1;
                }
            } else {
                $newNumber = 1;
            }

            // Add a small random component to reduce collision probability
            if ($i > 0) {
                $newNumber = $newNumber * 10 + rand(0, 9);
            }

            // Ensure we don't exceed reasonable limits
            $newNumber = $newNumber % 100000;

            $receiptNumber = "RCP-{$year}{$month}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Check if this receipt number already exists
            if (!self::where('receipt_number', $receiptNumber)->exists()) {
                return $receiptNumber;
            }
        }

        // If we still can't generate a unique number, use timestamp
        return "RCP-{$year}{$month}-" . substr(time(), -8);
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->paid_amount, 0, ',', '.');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('payment_date', $year);
    }

    public function scopeByMonth($query, $month, $year = null)
    {
        $year = $year ?: date('Y');
        return $query->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month);
    }

    public function scopeByProgramCategory($query, $category)
    {
        return $query->where('program_category', $category);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Note: program_id column doesn't exist in zakat_payments table
    // Program relationship is through campaigns or program_type_id
    // This relationship is disabled to prevent SQL errors
    // Use programType() relationship instead if needed
    // If you need to access program, use campaigns relationship


    // Event handling for notifications
    public static function boot()
    {
        parent::boot();

        // When a payment is created
        static::created(function ($payment) {
            // Create a notification for the muzakki
            if ($payment->muzakki) {
                Notification::createPaymentNotification($payment->muzakki, $payment, $payment->status);
            }
        });

        // When a payment is updated
        static::updated(function ($payment) {
            // Check if status has changed
            if ($payment->isDirty('status')) {
                // Create a notification for the muzakki
                if ($payment->muzakki) {
                    Notification::createPaymentNotification($payment->muzakki, $payment, $payment->status);
                }
            }
        });
    }
}
