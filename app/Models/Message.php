<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    const TYPE_TEXT = 'text';
    const TYPE_SYSTEM = 'system';
    const TYPE_LOAN_REQUEST = 'loan_request';
    const TYPE_LOAN_APPROVED = 'loan_approved';
    const TYPE_LOAN_DENIED = 'loan_denied';
    const TYPE_BOOK_RETURNED = 'book_returned';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'content',
        'type',
        'is_read',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('conversation', function ($q) use ($user) {
            $q
                ->where('participant_1_id', $user->id)
                ->orWhere('participant_2_id', $user->id);
        });
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    // Helper Methods
    public function markAsRead(): bool
    {
        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function isSystemMessage(): bool
    {
        return $this->type !== self::TYPE_TEXT;
    }

    public function getFormattedContentAttribute(): string
    {
        switch ($this->type) {
            case self::TYPE_LOAN_REQUEST:
                return '📚 Neue Ausleiheanfrage gesendet';
            case self::TYPE_LOAN_APPROVED:
                return '✅ Ausleihe genehmigt';
            case self::TYPE_LOAN_DENIED:
                return '❌ Ausleihe abgelehnt';
            case self::TYPE_BOOK_RETURNED:
                return '📚 Buch zurückgegeben';
            default:
                return $this->content;
        }
    }

    // Static Methods
    public static function createSystemMessage(Conversation $conversation, string $type, string $content, array $metadata = []): self
    {
        return self::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $conversation->participant_1_id,  // System messages from first participant
            'content' => $content,
            'type' => $type,
            'metadata' => $metadata,
        ]);
    }

    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_TEXT => 'Textnachricht',
            self::TYPE_SYSTEM => 'Systemnachricht',
            self::TYPE_LOAN_REQUEST => 'Ausleiheanfrage',
            self::TYPE_LOAN_APPROVED => 'Ausleihe genehmigt',
            self::TYPE_LOAN_DENIED => 'Ausleihe abgelehnt',
            self::TYPE_BOOK_RETURNED => 'Buch zurückgegeben',
        ];
    }
}
