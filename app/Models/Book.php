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
            self::STATUS_VERFUEGBAR => 'در دسترس',
            self::STATUS_ANGEFRAGT => 'درخواست شده',
            self::STATUS_AUSGELIEHEN => 'امانت داده شده',
            self::STATUS_RESERVIERT => 'رزرو شده',
        ];
    }
}
