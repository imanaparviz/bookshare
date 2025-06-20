<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'review',
        'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    // روابط (Relationships)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // دامنه‌ها (Scopes)
    public function scopePublic($query)
    {
        return $query->where('is_anonymous', false);
    }

    public function scopeWithReview($query)
    {
        return $query->whereNotNull('review')->where('review', '!=', '');
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    // متدهای کمکی (Helper Methods)
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getReviewerNameAttribute()
    {
        return $this->is_anonymous ? 'Anonymer Benutzer' : $this->user->name;
    }

    public static function getAverageRating($bookId)
    {
        return static::where('book_id', $bookId)->avg('rating');
    }

    public static function getRatingDistribution($bookId)
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = static::where('book_id', $bookId)
                ->where('rating', $i)
                ->count();
        }
        return $distribution;
    }

    public static function getTotalRatings($bookId)
    {
        return static::where('book_id', $bookId)->count();
    }

    // Validation Rules
    public static function validationRules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean',
        ];
    }
}
