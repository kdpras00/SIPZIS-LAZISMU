<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Program extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'target_amount',
        'status',
        'photo',
        'image_url',
        'slug',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
    ];

    // ========================
    // 🔗 Relationships
    // ========================
    public function programType()
    {
        return $this->belongsTo(ProgramType::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    // Note: program_id column doesn't exist in zakat_payments table
    // Payments are linked via campaigns, not directly to programs
    // This method returns a query builder that always returns empty results
    // Use campaigns relationship instead to get payments
    public function zakatPayments()
    {
        // Return query builder with condition that's always false
        // This prevents "Column not found" SQL errors when accessing the relationship
        return ZakatPayment::where('id', '<', 0); // Always returns empty result
    }

    // Relationship to get zakat distributions related to this program
    public function zakatDistributions()
    {
        return $this->hasMany(ZakatDistribution::class, 'program_name', 'category');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // ========================
    // 🧮 Accessors (Computed Fields)
    // ========================

    /**
     * Get the full URL for the program photo.
     * Supports both CDN URLs and local storage paths.
     */
    public function getImageUrlAttribute()
    {
        // Get image_url or photo from attributes directly to avoid infinite loop
        $imageUrl = isset($this->attributes['image_url']) ? $this->attributes['image_url'] : null;
        $photo = isset($this->attributes['photo']) ? $this->attributes['photo'] : null;
        
        // Use image_url if available (for CDN/external URLs), otherwise fallback to photo
        $imagePath = $imageUrl ?: $photo;

        // If image path is empty, return a default image
        if (empty($imagePath)) {
            return asset('img/masjid.webp');
        }

        // Check if image path is a full URL (CDN)
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // For local storage paths, use Storage::url() for proper URL generation
        // Storage::url() automatically handles the 'storage/' prefix
        return Storage::url($imagePath);
    }

    // Total dana terkumpul dari semua campaign yang published
    public function getTotalCollectedAttribute()
    {
        // Note: program_id doesn't exist in zakat_payments table
        // Only get payments from campaigns associated with this program
        $campaignPayments = $this->campaigns()
            ->published()
            ->with('zakatPayments')
            ->get()
            ->sum(function ($campaign) {
                return $campaign->zakatPayments()->sum('paid_amount');
            });

        return $campaignPayments;
    }

    // Total dana yang telah didistribusikan
    public function getTotalDistributedAttribute()
    {
        return $this->zakatDistributions()->sum('amount');
    }

    // Total dana bersih (terkumpul - didistribusikan)
    public function getNetTotalCollectedAttribute()
    {
        return max(0, $this->total_collected - $this->total_distributed);
    }

    // Format total terkumpul dalam bentuk rupiah
    public function getFormattedTotalCollectedAttribute()
    {
        return 'Rp ' . number_format($this->net_total_collected ?? 0, 0, ',', '.');
    }

    // Total target (ambil dari program langsung atau dari campaign)
    public function getTotalTargetAttribute()
    {
        if ($this->target_amount > 0) {
            return $this->target_amount;
        }

        return $this->campaigns()
            ->published()
            ->sum('target_amount');
    }

    // Format total target dalam bentuk rupiah
    public function getFormattedTotalTargetAttribute()
    {
        return 'Rp ' . number_format($this->total_target ?? 0, 0, ',', '.');
    }

    // Persentase progress (0–100%)
    public function getProgressPercentageAttribute()
    {
        if ($this->total_target <= 0) {
            return 0;
        }

        // Use net collected amount for progress calculation
        return min(100, ($this->net_total_collected / $this->total_target) * 100);
    }

    // Ensure slug is always available
    public function getSlugAttribute($value)
    {
        // If slug is not set, generate it from the name
        if (!$value) {
            return Str::slug($this->name);
        }

        return $value;
    }

    // ========================
    // 🔍 Scopes
    // ========================
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($program) {
            // Generate slug if not provided
            if (empty($program->slug)) {
                $baseSlug = Str::slug($program->name);
                $slug = $baseSlug;
                $counter = 1;

                // Ensure slug is unique
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $program->slug = $slug;
            }
        });

        // When a program is created
        static::created(function ($program) {
            // Create a notification for all users about the new program
            // This would typically be done in a scheduled job or event listener
        });

        // When a program is updated
        static::updated(function ($program) {
            // Check if status has changed to active
            if ($program->isDirty('status') && $program->status === 'active') {
                // Create a notification for all users about the program being active
                // This would typically be done in a scheduled job or event listener
            }
        });
    }
}
