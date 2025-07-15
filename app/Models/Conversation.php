<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'loan_id',
        'participant_1_id',
        'participant_2_id',
        'last_message_at',
        'is_active',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function participant1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_1_id');
    }

    public function participant2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_2_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest('created_at')->limit(1);
    }

    // Helper Methods
    public function getOtherParticipant(User $user): User
    {
        if ($this->participant_1_id === $user->id) {
            return $this->participant2;
        }
        return $this->participant1;
    }

    public function isParticipant(User $user): bool
    {
        return $this->participant_1_id === $user->id || $this->participant_2_id === $user->id;
    }

    public function getUnreadCount(User $user): int
    {
        return $this
            ->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
    }

    // Scopes
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query
            ->where('participant_1_id', $user->id)
            ->orWhere('participant_2_id', $user->id);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeWithLatestMessage(Builder $query): Builder
    {
        return $query->with(['latestMessage', 'participant1', 'participant2', 'loan.book']);
    }

    // Static Methods
    public static function findOrCreateForLoan(Loan $loan): self
    {
        return self::firstOrCreate([
            'loan_id' => $loan->id,
        ], [
            'participant_1_id' => $loan->borrower_id,
            'participant_2_id' => $loan->lender_id,
            'is_active' => true,
        ]);
    }
}
