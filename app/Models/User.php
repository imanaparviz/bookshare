<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Online-Status-Konstanten
    const STATUS_ACTIVE = 'active';
    const STATUS_AWAY = 'away';
    const STATUS_BUSY = 'busy';
    const STATUS_OFFLINE = 'offline';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'last_seen_at',
        'is_online',
        'status',
        'message_notifications',
        'email_notifications',
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
            'last_seen_at' => 'datetime',
            'is_online' => 'boolean',
            'message_notifications' => 'boolean',
            'email_notifications' => 'boolean',
        ];
    }

    /**
     * دریافت کتاب‌های متعلق به کاربر
     */
    public function ownedBooks(): HasMany
    {
        return $this->hasMany(Book::class, 'owner_id');
    }

    /**
     * دریافت همه کتاب‌های متعلق به این کاربر
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'owner_id');
    }

    /**
     * دریافت امانت‌هایی که این کاربر امانت‌گیرنده است
     */
    public function borrowedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'borrower_id');
    }

    /**
     * دریافت امانت‌هایی که این کاربر امانت‌دهنده است
     */
    public function lentLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'lender_id');
    }

    /**
     * دریافت تمام رتبه‌بندی‌های این کاربر
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * دریافت گفتگوهای این کاربر
     */
    public function conversations(): HasMany
    {
        return $this
            ->hasMany(Conversation::class, 'participant_1_id')
            ->orWhere('participant_2_id', $this->id);
    }

    /**
     * دریافت پیام‌های ارسال شده توسط کاربر
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Online-Präsenz-Methoden
    public function updateLastSeen(): void
    {
        $this->update([
            'last_seen_at' => now(),
            'is_online' => true,
        ]);
    }

    public function setOffline(): void
    {
        $this->update([
            'is_online' => false,
            'status' => self::STATUS_OFFLINE,
        ]);
    }

    public function isOnline(): bool
    {
        return $this->is_online && $this->last_seen_at > now()->subMinutes(5);
    }

    public function getOnlineStatusAttribute(): string
    {
        if ($this->isOnline()) {
            return 'Online';
        }

        if ($this->last_seen_at) {
            $diff = $this->last_seen_at->diffForHumans();
            return "Zuletzt gesehen: {$diff}";
        }

        return 'Offline';
    }

    public function getResponseTimeAttribute(): string
    {
        // Berechne durchschnittliche Antwortzeit basierend auf Messaging-Verhalten
        $avgResponseMinutes = $this->calculateAverageResponseTime();

        if ($avgResponseMinutes < 30) {
            return 'antwortet meist innerhalb von 30 Minuten';
        } elseif ($avgResponseMinutes < 120) {
            return 'antwortet meist innerhalb von 2 Stunden';
        } elseif ($avgResponseMinutes < 1440) {
            return 'antwortet meist innerhalb eines Tages';
        } else {
            return 'antwortet gelegentlich';
        }
    }

    private function calculateAverageResponseTime(): int
    {
        // Vereinfachte Berechnung - könnte komplexer werden
        return 60;  // Default: 60 Minuten
    }

    // Messaging-Methoden
    public function getUnreadMessagesCount(): int
    {
        return Message::whereHas('conversation', function ($query) {
            $query
                ->where('participant_1_id', $this->id)
                ->orWhere('participant_2_id', $this->id);
        })
            ->where('sender_id', '!=', $this->id)
            ->where('is_read', false)
            ->count();
    }

    public function hasUnreadMessages(): bool
    {
        return $this->getUnreadMessagesCount() > 0;
    }

    // Scopes
    public function scopeOnline(Builder $query): Builder
    {
        return $query
            ->where('is_online', true)
            ->where('last_seen_at', '>', now()->subMinutes(5));
    }

    public function scopeRecentlyActive(Builder $query): Builder
    {
        return $query->where('last_seen_at', '>', now()->subDay());
    }

    // Static Methods
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'Aktiv',
            self::STATUS_AWAY => 'Abwesend',
            self::STATUS_BUSY => 'Beschäftigt',
            self::STATUS_OFFLINE => 'Offline',
        ];
    }
}
