<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZakatDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'distribution_code',
        'mustahik_id',
        'amount',
        'distribution_type',
        'goods_description',
        'distribution_date',
        'notes',
        'program_id',
        'program_name',
        'distributed_by',
        'location',
        'is_received',
        'received_date',
        'received_by_name',
        'received_notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'distribution_date' => 'date',
        'received_date' => 'date',
        'is_received' => 'boolean',
    ];

    // Relationships
    public function mustahik()
    {
        return $this->belongsTo(Mustahik::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function distributedBy()
    {
        return $this->belongsTo(User::class, 'distributed_by');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // Methods
    public static function generateDistributionCode()
    {
        $year = date('Y');
        $lastDistribution = self::where('distribution_code', 'like', "DIST-{$year}-%")
            ->orderBy('distribution_code', 'desc')
            ->first();

        if ($lastDistribution) {
            $lastNumber = (int) substr($lastDistribution->distribution_code, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "DIST-{$year}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getDistributionTypeColorAttribute()
    {
        $colors = [
            'cash' => 'success',
            'goods' => 'info',
            'voucher' => 'warning',
            'service' => 'primary'
        ];

        return $colors[$this->distribution_type] ?? 'secondary';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_received) {
            return '<span class="badge bg-success">Sudah Diterima</span>';
        }
        return '<span class="badge bg-warning">Belum Diterima</span>';
    }

    // Scopes
    public function scopeReceived($query)
    {
        return $query->where('is_received', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_received', false);
    }

    public function scopeByProgram($query, $program)
    {
        return $query->where('program_name', $program);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('distribution_type', $type);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('distribution_date', $year);
    }

    // Event handling for notifications
    public static function boot()
    {
        parent::boot();

        // When a distribution is created
        static::created(function ($distribution) {
            // Create a notification for related muzakki (if any)
            // This would require linking distributions to specific muzakki
            // For now, we'll leave this as a placeholder
        });

        // When a distribution is updated
        static::updated(function ($distribution) {
            // Check if distribution has been marked as received
            if ($distribution->isDirty('is_received') && $distribution->is_received) {
                // Create a notification for related muzakki (if any)
                // This would require linking distributions to specific muzakki
                // For now, we'll leave this as a placeholder
            }
        });
    }
}
