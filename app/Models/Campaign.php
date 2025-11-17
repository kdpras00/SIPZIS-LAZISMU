<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'description',
        'program_category',
        'program_id',
        'target_amount',
        'collected_amount',
        'photo',
        'status',
        'end_date',
        'created_by',
        'is_published',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'end_date' => 'date',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function zakatPayments()
    {
        // If campaign has program_id, use it; otherwise fallback to program_category
        if ($this->program_id) {
            return $this->hasMany(ZakatPayment::class, 'program_id', 'program_id')
                ->where('status', 'completed');
        }
        
        // Fallback to program_category for backward compatibility
        return $this->hasMany(ZakatPayment::class, 'program_category', 'program_category')
            ->whereNotNull('program_category')
            ->where('status', 'completed');
    }

    public function zakatDistributions()
    {
        return $this->hasMany(ZakatDistribution::class, 'program_name', 'program_category');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if (empty($this->photo)) {
            return asset('img/masjid.webp');
        }

        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }

        return asset('storage/' . ltrim($this->photo, '/'));
    }

    public function getCollectedAmountAttribute()
    {
        // If campaign has program_id, get payments for that program
        if ($this->program_id) {
            return ZakatPayment::where('program_id', $this->program_id)
                ->where('status', 'completed')
                // Only count payments created AFTER the campaign was created
                ->where('created_at', '>=', $this->created_at)
                ->sum('paid_amount');
        }
        
        // Fallback to old method using program_category
        // Only count payments created AFTER the campaign was created
        return ZakatPayment::where('program_category', $this->program_category)
            ->whereNotNull('program_category')
            ->where('status', 'completed')
            ->where('created_at', '>=', $this->created_at)
            ->sum('paid_amount');
    }

    public function getDistributedAmountAttribute()
    {
        return $this->zakatDistributions()->sum('amount');
    }

    public function getNetCollectedAmountAttribute()
    {
        return max(0, $this->collected_amount - $this->distributed_amount);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, ($this->net_collected_amount / $this->target_amount) * 100);
    }

    public function getFormattedTargetAmountAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCollectedAmountAttribute()
    {
        return 'Rp ' . number_format($this->net_collected_amount, 0, ',', '.');
    }

    public function getDonorsCountAttribute()
    {
        return $this->zakatPayments()->count();
    }

    /**
     * Calculate remaining days until campaign ends
     * 
     * Returns:
     * - Positive number: days remaining
     * - 0: campaign ends today
     * - -1: campaign has already ended
     * - null: no end date set
     */
    public function getRemainingDaysAttribute()
    {
        // Jika tidak ada end_date, return null
        if (!$this->end_date) {
            return null;
        }

        $endDate = Carbon::parse($this->end_date)->endOfDay(); // Gunakan end of day untuk akurasi
        $now = Carbon::now();

        // Jika sudah melewati end_date, return -1
        if ($now->isAfter($endDate)) {
            return -1;
        }

        // Hitung selisih hari dari hari ini ke end_date
        // PENTING: Urutan parameter benar! (from, to)
        $remainingDays = $now->diffInDays($endDate, absolute: false);

        return (int) $remainingDays;
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->startOfDay());
            });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('program_category', $category);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function isExpired(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        return Carbon::parse($this->end_date)->isPast();
    }

    /**
     * Mark campaign as completed
     */
    public function markAsCompleted(): bool
    {
        if ($this->isExpired() && $this->status === 'published') {
            return $this->update(['status' => 'completed']);
        }

        return false;
    }

    /**
     * Check if campaign should be automatically completed and do so if needed
     */
    public function checkAndCompleteIfExpired(): bool
    {
        if ($this->isExpired() && $this->status === 'published') {
            return $this->markAsCompleted();
        }

        return false;
    }

    /**
     * Scope untuk mendapatkan campaign yang sudah expired
     */
    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now()->startOfDay());
    }

    /**
     * Boot method untuk event handling
     */
    protected static function boot()
    {
        parent::boot();

        // When a campaign is created
        static::created(function ($campaign) {
            // Buat notifikasi untuk semua muzakki tentang campaign baru yang published
            if ($campaign->status === 'published') {
                $muzakkiList = \App\Models\Muzakki::whereNotNull('user_id')->get();
                foreach ($muzakkiList as $muzakki) {
                    if ($muzakki->user) {
                        // Langsung gunakan campaign sebagai notifiable
                        \App\Models\Notification::createProgramNotification($muzakki->user, $campaign, 'program');
                    }
                }
            }
        });

        // When a campaign is updated
        static::updated(function ($campaign) {
            // Check if status has changed to published
            if ($campaign->isDirty('status') && $campaign->status === 'published') {
                // Buat notifikasi untuk semua muzakki tentang campaign yang dipublish
                $muzakkiList = \App\Models\Muzakki::whereNotNull('user_id')->get();
                foreach ($muzakkiList as $muzakki) {
                    if ($muzakki->user) {
                        // Langsung gunakan campaign sebagai notifiable
                        \App\Models\Notification::createProgramNotification($muzakki->user, $campaign, 'program');
                    }
                }
            }
        });
    }
}
