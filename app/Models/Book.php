<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // ثابت‌های وضعیت
    const STATUS_VERFUEGBAR = 'verfügbar';  // در دسترس
    const STATUS_AUSGELIEHEN = 'ausgeliehen';  // امانت داده شده
    const STATUS_RESERVIERT = 'reserviert';  // رزرو شده
    const STATUS_ANGEFRAGT = 'angefragt';  // درخواست شده (جدید اضافه شده برای درخواست امانت)

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'genre',
        'publication_year',
        'language',
        'condition',
        'status',
        'owner_id',
        'image_path',
    ];

    protected $casts = [
        'publication_year' => 'integer',
    ];

    // روابط
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function currentLoan(): HasMany
    {
        return $this->hasMany(Loan::class)->where('status', 'aktiv');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    // دامنه‌ها (Scopes)
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_VERFUEGBAR);
    }

    public function scopeByGenre($query, $genre)
    {
        return $query->where('genre', $genre);
    }

    // متدهای کمکی
    public static function getStatusOptions()
    {
        return [
            self::STATUS_VERFUEGBAR => 'Verfügbar',
            self::STATUS_ANGEFRAGT => 'Angefragt',
            self::STATUS_AUSGELIEHEN => 'Ausgeliehen',
            self::STATUS_RESERVIERT => 'Reserviert',
        ];
    }

    // Rating helper methods
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function getTotalRatingsAttribute()
    {
        return $this->ratings()->count();
    }

    public function getRatingStarsAttribute()
    {
        $avgRating = round($this->average_rating, 1);
        return str_repeat('★', (int) $avgRating) . str_repeat('☆', 5 - (int) $avgRating);
    }

    public function getUserRating($userId)
    {
        return $this->ratings()->where('user_id', $userId)->first();
    }

    public function hasUserRated($userId)
    {
        return $this->ratings()->where('user_id', $userId)->exists();
    }
}
