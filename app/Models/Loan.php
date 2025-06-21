<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    // ثابت‌های وضعیت
    const STATUS_ANGEFRAGT = 'angefragt';  // درخواست شده
    const STATUS_AKTIV = 'aktiv';  // فعال
    const STATUS_ABGELEHNT = 'abgelehnt';  // رد شده
    const STATUS_STORNIERT = 'storniert';  // لغو شده (توسط امانت‌گیرنده)
    const STATUS_ZURUECKGEGEBEN = 'zurückgegeben';  // برگردانده شده
    const STATUS_UEBERFAELLIG = 'überfällig';  // معوقه

    protected $fillable = [
        'book_id',
        'borrower_id',
        'lender_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
        'message',
        'contact_info',
        'pickup_method',
        'requested_duration_weeks',
        'lender_response',
        'responded_at',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'responded_at' => 'datetime',
    ];

    // روابط
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

    // دامنه‌ها (Scopes)
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

    // دسترسی‌گرها (Accessors)
    public function getIsOverdueAttribute()
    {
        return $this->status === self::STATUS_AKTIV && $this->due_date < Carbon::now();
    }

    // متدهای کمکی
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ANGEFRAGT => 'درخواست شده',
            self::STATUS_AKTIV => 'فعال',
            self::STATUS_ABGELEHNT => 'رد شده',
            self::STATUS_STORNIERT => 'لغو شده',
            self::STATUS_ZURUECKGEGEBEN => 'برگردانده شده',
            self::STATUS_UEBERFAELLIG => 'معوقه',
        ];
    }
}
