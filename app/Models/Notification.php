<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'muzakki_id',
        'type',
        'title',
        'message',
        'icon',
        'color',
        'is_read',
        'read_at',
        'notifiable_type',
        'notifiable_id',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array'
    ];

    protected $dispatchesEvents = [
        'created' => \App\Events\NotificationCreated::class,
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForMuzakki($query, $muzakkiId)
    {
        return $query->where('muzakki_id', $muzakkiId);
    }

    public function scopeByTypes($query, $types)
    {
        return $query->whereIn('type', $types);
    }

    // Methods
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    public function markAsUnread()
    {
        if ($this->is_read) {
            $this->update([
                'is_read' => false,
                'read_at' => null
            ]);
        }
    }

    public function getIconClassAttribute()
    {
        $icons = [
            'payment' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'distribution' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
            'program' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
            'account' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'reminder' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'message' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'
        ];

        return $icons[$this->type] ?? $icons['message'];
    }

    public function getColorClassAttribute()
    {
        $colors = [
            'payment' => 'green',
            'distribution' => 'blue',
            'program' => 'purple',
            'account' => 'yellow',
            'reminder' => 'orange',
            'message' => 'indigo'
        ];

        return $colors[$this->type] ?? $colors['message'];
    }

    public function getActionUrlAttribute(): string
    {
        try {
            return match ($this->type) {
                'payment' => $this->paymentActionUrl(),
                'distribution' => route('dashboard.amalanku'),
                'program' => $this->programActionUrl(),
                'account' => $this->accountActionUrl(),
                'reminder' => $this->reminderActionUrl(),
                'message' => route('notifications.index'),
                default => route('notifications.index'),
            };
        } catch (\Throwable $e) {
            return route('notifications.index');
        }
    }

    protected function paymentActionUrl(): string
    {
        $paymentId = $this->data['payment_id'] ?? $this->notifiable_id;

        if ($paymentId) {
            return route('payments.show', $paymentId);
        }

        return route('dashboard.transactions');
    }

    protected function programActionUrl(): string
    {
        if ($campaignId = $this->data['campaign_id'] ?? null) {
            $campaign = Campaign::find($campaignId);
            if ($campaign) {
                $category = $campaign->program_category ?? 'zakat';
                return route('campaigns.show', [$category, $campaign->id]);
            }
        }

        if ($programId = $this->data['program_id'] ?? $this->notifiable_id) {
            $program = Program::find($programId);
            if ($program && $program->slug) {
                return route('program.show', $program->slug);
            }
        }

        return route('program');
    }

    protected function accountActionUrl(): string
    {
        $eventType = $this->data['event_type'] ?? null;

        return match ($eventType) {
            'profile' => route('profile.edit'),
            'password' => route('dashboard.management'),
            default => route('dashboard.management'),
        };
    }

    protected function reminderActionUrl(): string
    {
        $reminderType = $this->data['reminder_type'] ?? null;

        return match ($reminderType) {
            'zakat' => route('donation'),
            'balance' => route('dashboard.amalanku'),
            default => route('dashboard'),
        };
    }

    // Group notifications by type
    public static function groupByType($notifications)
    {
        return $notifications->groupBy('type');
    }

    // Get notification types with counts
    public static function getTypesWithCounts($userId = null, $muzakkiId = null)
    {
        $query = self::select('type')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('type');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($muzakkiId) {
            $query->where('muzakki_id', $muzakkiId);
        }

        return $query->get()->keyBy('type');
    }

    // Static methods for creating different types of notifications
    public static function createPaymentNotification($muzakki, $payment, $status)
    {
        // Tentukan jenis pembayaran berdasarkan program_category
        $paymentType = 'Pembayaran'; // Default
        if ($payment->program_category) {
            $category = strtolower(trim($payment->program_category));
            
            // Cek kategori utama (exact match atau dimulai dengan kategori tersebut)
            if ($category === 'zakat' || strpos($category, 'zakat-') === 0) {
                $paymentType = 'Zakat';
            } elseif ($category === 'infaq' || strpos($category, 'infaq-') === 0) {
                $paymentType = 'Infaq';
            } elseif ($category === 'shadaqah' || strpos($category, 'shadaqah-') === 0 || 
                      $category === 'sedekah' || strpos($category, 'sedekah-') === 0) {
                $paymentType = 'Shadaqah';
            } elseif (in_array($category, ['pendidikan', 'kesehatan', 'ekonomi', 'sosial-dakwah', 'kemanusiaan', 'lingkungan'])) {
                // Program Pilar: format nama kategori dengan huruf kapital di awal
                $pilarNames = [
                    'pendidikan' => 'Pendidikan',
                    'kesehatan' => 'Kesehatan',
                    'ekonomi' => 'Ekonomi',
                    'sosial-dakwah' => 'Sosial & Dakwah',
                    'kemanusiaan' => 'Kemanusiaan',
                    'lingkungan' => 'Lingkungan'
                ];
                $paymentType = $pilarNames[$category] ?? ucfirst(str_replace('-', ' ', $category));
            } else {
                // Untuk kategori lain, gunakan format yang lebih ramah
                $paymentType = ucfirst(str_replace('-', ' ', $category));
            }
        }

        $messages = [
            'completed' => 'Pembayaran ' . $paymentType . ' Anda telah berhasil diverifikasi.',
            'failed' => 'Pembayaran ' . $paymentType . ' Anda gagal diproses, silakan coba kembali.',
            'pending' => 'Menunggu konfirmasi pembayaran ' . $paymentType . ' melalui ' . ($payment->payment_method ?? 'transfer bank') . '.'
        ];

        $titles = [
            'completed' => '✅ Pembayaran Berhasil',
            'failed' => '❌ Pembayaran Gagal',
            'pending' => '⏳ Menunggu Konfirmasi'
        ];

        return self::create([
            'muzakki_id' => $muzakki->id,
            'user_id' => $muzakki->user_id,
            'type' => 'payment',
            'title' => $titles[$status],
            'message' => $messages[$status],
            'notifiable_type' => ZakatPayment::class,
            'notifiable_id' => $payment->id,
            'data' => [
                'payment_id' => $payment->id,
                'status' => $status,
                'amount' => $payment->paid_amount,
                'program_category' => $payment->program_category,
                'payment_type' => $paymentType
            ]
        ]);
    }

    public static function createDistributionNotification($muzakki, $distribution)
    {
        $message = 'Zakat Anda telah disalurkan kepada mustahik di wilayah ' . ($distribution->location ?? 'yang membutuhkan') . '.';

        return self::create([
            'muzakki_id' => $muzakki->id,
            'user_id' => $muzakki->user_id,
            'type' => 'distribution',
            'title' => '📬 Zakat Telah Disalurkan',
            'message' => $message,
            'notifiable_type' => ZakatDistribution::class,
            'notifiable_id' => $distribution->id,
            'data' => [
                'distribution_id' => $distribution->id,
                'location' => $distribution->location,
                'amount' => $distribution->amount
            ]
        ]);
    }

    // 🔴 PERBAIKAN: Tambahkan muzakki_id untuk program notification
    // Method ini bisa menerima Program atau Campaign
    public static function createProgramNotification($user, $notifiable, $eventType)
    {
        $muzakki = $user->muzakki; // Ambil muzakki profile

        // Tentukan nama dan tipe notifiable (Program atau Campaign)
        $notifiableName = null;
        $notifiableType = null;
        $notifiableId = null;

        if ($notifiable instanceof \App\Models\Program) {
            $notifiableName = $notifiable->name ?? 'Program Baru';
            $notifiableType = Program::class;
            $notifiableId = $notifiable->id;
        } elseif ($notifiable instanceof \App\Models\Campaign) {
            $notifiableName = $notifiable->title ?? 'Campaign Baru';
            $notifiableType = \App\Models\Campaign::class;
            $notifiableId = $notifiable->id;
        } else {
            $notifiableName = 'Program Baru';
            $notifiableType = Program::class;
            $notifiableId = $notifiable->id ?? null;
        }

        $messages = [
            'event' => 'Kajian Jumat besok pukul 09.00 di Aula Utama.',
            'program' => ($notifiable instanceof \App\Models\Campaign ? 'Campaign' : 'Program') . ' ' . $notifiableName . ' telah tersedia. Mari berpartisipasi dalam ' . ($notifiable instanceof \App\Models\Campaign ? 'campaign' : 'program') . ' ini!'
        ];

        $titles = [
            'event' => '📅 Kegiatan Mendatang',
            'program' => $notifiable instanceof \App\Models\Campaign ? '🎯 Campaign Baru' : '🕌 Program Baru'
        ];

        return self::create([
            'user_id' => $user->id,
            'muzakki_id' => $muzakki ? $muzakki->id : null, // ✅ TAMBAHKAN INI
            'type' => 'program',
            'title' => $titles[$eventType],
            'message' => $messages[$eventType],
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
            'data' => [
                'program_id' => $notifiable instanceof \App\Models\Program ? $notifiable->id : ($notifiable->program_id ?? null),
                'campaign_id' => $notifiable instanceof \App\Models\Campaign ? $notifiable->id : null,
                'program_name' => $notifiableName,
                'event_type' => $eventType
            ]
        ]);
    }

    public static function createAccountNotification($user, $eventType, $muzakki = null)
    {
        // Ambil profil muzakki yang terhubung dengan user
        // Jika muzakki tidak diberikan, ambil dari relationship
        if (!$muzakki) {
            $muzakki = $user->muzakki;
        }

        $messages = [
            'profile' => 'Selamat datang! Lengkapi profil Anda untuk mempermudah transaksi donasi.',
            'password' => 'Kata sandi Anda berhasil diubah.'
        ];

        $titles = [
            'profile' => '👋 Selamat Datang',
            'password' => '🔒 Perubahan Kata Sandi'
        ];

        return self::create([
            'user_id' => $user->id,
            'muzakki_id' => $muzakki ? $muzakki->id : null,
            'type' => 'account',
            'title' => $titles[$eventType],
            'message' => $messages[$eventType],
            'data' => [
                'event_type' => $eventType
            ]
        ]);
    }

    public static function createReminderNotification($muzakki, $reminderType)
    {
        $messages = [
            'zakat' => 'Sudah waktunya membayar zakat penghasilan bulan ini.',
            'balance' => 'Saldo zakat Anda tersisa Rp200.000, ingin disalurkan?'
        ];

        $titles = [
            'zakat' => '🕋 Waktu Zakat',
            'balance' => '💡 Saldo Zakat'
        ];

        return self::create([
            'muzakki_id' => $muzakki->id,
            'user_id' => $muzakki->user_id,
            'type' => 'reminder',
            'title' => $titles[$reminderType],
            'message' => $messages[$reminderType],
            'data' => [
                'reminder_type' => $reminderType
            ]
        ]);
    }

    // 🔴 PERBAIKAN: Tambahkan muzakki_id untuk message notification
    public static function createMessageNotification($user, $message, $sender = 'Admin')
    {
        $muzakki = $user->muzakki; // Ambil muzakki profile

        return self::create([
            'user_id' => $user->id,
            'muzakki_id' => $muzakki ? $muzakki->id : null, // ✅ TAMBAHKAN INI
            'type' => 'message',
            'title' => '📩 Pesan Baru',
            'message' => $sender . ': ' . $message,
            'data' => [
                'sender' => $sender,
                'message' => $message
            ]
        ]);
    }

    protected static function booted()
    {
        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('created_at', 'desc');
        });
    }
}
