<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    // Status constants
    const STATUS_ANGEFRAGT = 'angefragt';
    const STATUS_AKTIV = 'aktiv';
    const STATUS_ABGELEHNT = 'abgelehnt';
    const STATUS_ZURUECKGEGEBEN = 'zurückgegeben';
    const STATUS_UEBERFAELLIG = 'überfällig';

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
        return $query->where('status', self::STATUS_AKTIV);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_ANGEFRAGT);
    }

    public function scopeOverdue($query)
    {
        return $query
            ->where('due_date', '<', Carbon::now())
            ->where('status', self::STATUS_AKTIV);
    }

    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_ZURUECKGEGEBEN);
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        return $this->status === self::STATUS_AKTIV && $this->due_date < Carbon::now();
    }

    // Helper methods
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ANGEFRAGT => 'Angefragt',
            self::STATUS_AKTIV => 'Aktiv',
            self::STATUS_ABGELEHNT => 'Abgelehnt',
            self::STATUS_ZURUECKGEGEBEN => 'Zurückgegeben',
            self::STATUS_UEBERFAELLIG => 'Überfällig',
        ];
    }
}
