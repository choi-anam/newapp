<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetOtp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'email',
        'otp',
        'channel',
        'expires_at',
        'is_used',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_used' => 'boolean',
        ];
    }

    /**
     * Relationship: Belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if OTP is valid (not used and not expired)
     */
    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    /**
     * Mark OTP as used
     */
    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }

    /**
     * Get latest valid OTP for user and email
     */
    public static function getValidOtp($userId, $email)
    {
        return self::where('user_id', $userId)
            ->where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest('created_at')
            ->first();
    }

    /**
     * Clean expired OTPs
     */
    public static function cleanExpiredOtps(): int
    {
        return self::where('expires_at', '<=', now())
            ->delete();
    }
}
