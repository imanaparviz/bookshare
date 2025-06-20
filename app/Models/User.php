<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
}
