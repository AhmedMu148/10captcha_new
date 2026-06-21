<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'balance_5d',
        'min_balance_5d',
        'min_balance_email',
        'lastactivity',
        'hash',
        'api_key',
        'ref_url',
        'date',
        'api',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function detail()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    public function affiliate()
    {
        return $this->hasOne(Affiliate::class, 'user_id');
    }

    public function getFnameAttribute()
    {
        return $this->detail?->fname;
    }

    public function getLnameAttribute()
    {
        return $this->detail?->lname;
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 1:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">Active</span>';
            case 2:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">Verified</span>';
            case 3:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">Suspended</span>';
            case 4:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">Frozen</span>';
            default:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">Unknown</span>';
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification);
    }

    /**
     * Mark the user's email as verified and update status.
     *
     * @return bool
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'status' => 2, // Verified
        ])->save();
    }
}
