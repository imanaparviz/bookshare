<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
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

    // Relationships
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

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'verfügbar');
    }

    public function scopeByGenre($query, $genre)
    {
        return $query->where('genre', $genre);
    }
}
