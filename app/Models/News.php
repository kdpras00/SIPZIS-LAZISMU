<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'slug',
        'is_published',
        'excerpt',
        'author_id'
    ];

    protected $appends = [
        'image_url',
        'formatted_date'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Get the author of the news article
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get image URL (support CDN dan Storage Public)
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://via.placeholder.com/400x250?text=No+Image';
        }

        $image = trim($this->image);

        // Jika sudah URL CDN (http:// atau https://)
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        // Jika path lokal, gunakan Storage URL
        if (!empty($image)) {
            return Storage::url($image);
        }

        return 'https://via.placeholder.com/400x250?text=No+Image';
    }

    /**
     * Accessor untuk image_url (alias getImageUrlAttribute)
     */
    public function getImageAttribute($value)
    {
        return $value;
    }

    /**
     * Generate slug from title
     */
    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "$slug%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Scope for published news
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get formatted publication date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Get excerpt or generate from content
     */
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 150);
    }
}
