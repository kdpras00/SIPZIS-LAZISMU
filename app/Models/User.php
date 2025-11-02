<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function muzakki()
    {
        return $this->hasOne(Muzakki::class);
    }

    public function verifiedMustahik()
    {
        return $this->hasMany(Mustahik::class, 'verified_by');
    }

    public function receivedPayments()
    {
        return $this->hasMany(ZakatPayment::class, 'received_by');
    }

    public function distributions()
    {
        return $this->hasMany(ZakatDistribution::class, 'distributed_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }


    public function isMuzakki()
    {
        return $this->role === 'muzakki';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Get count of unread notifications
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->unread()->count();
    }

    // Get latest notifications
    public function getLatestNotifications($limit = 10)
    {
        return $this->notifications()->latest()->limit($limit)->get();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Event handling for notifications
    public static function boot()
    {
        parent::boot();

        // When a user is updated
        static::updated(function ($user) {
            // Check if password has changed
            if ($user->isDirty('password')) {
                // Create a notification about password change
                Notification::createAccountNotification($user, 'password');
            }
        });
    }
}
