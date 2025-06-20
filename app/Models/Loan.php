<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'book_id',
        'borrower_id',
        'lender_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    // Relationships
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function lender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktiv');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'angefragt');
    }

    public function scopeOverdue($query)
    {
        return $query
            ->where('due_date', '<', Carbon::now())
            ->where('status', 'aktiv');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'zurückgegeben');
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        return $this->status === 'aktiv' && $this->due_date < Carbon::now();
    }
}
