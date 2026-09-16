<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'checkin_code',
        'profile_photo_path',
        'profile_photo_change_count',
        'joined_at',
        'expires_at',
        'last_membership_reminder_at',
        'notes',
    ];

    protected $casts = [
        'joined_at' => 'date',
        'expires_at' => 'date',
        'last_membership_reminder_at' => 'datetime',
        'profile_photo_change_count' => 'integer',
    ];

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getProfileInitialsAttribute(): string
    {
        return collect(explode(' ', $this->full_name))
            ->map(fn ($n) => mb_substr($n, 0, 1))
            ->take(2)
            ->join('');
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (! $this->profile_photo_path) {
            return null;
        }

        $path = ltrim($this->profile_photo_path, '/');
        $userStorageExists = file_exists(base_path('user/storage/app/public/' . $path));
        $rootStorageExists = file_exists(storage_path('app/public/' . $path));

        return ($userStorageExists || $rootStorageExists)
            ? route('member.profile-photo.show', ['path' => $path])
            : asset('storage/' . $path);
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MembershipSubscription::class, 'member_id')->latest('start_date');
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->where('status', 'active')->first();
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class, 'member_id');
    }

    public function verifiedCheckins(): HasMany
    {
        return $this->checkins()
            ->where('verification_status', 'verified')
            ->latest('checked_in_at');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'member_id')->latest('transaction_at');
    }

    public function productTransactions(): HasMany
    {
        return $this->transactions()->where('type', Transaction::TYPE_PRODUCT_SALE);
    }

    public function renewalRequests(): HasMany
    {
        return $this->hasMany(MembershipRenewalRequest::class, 'member_id')->latest();
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(MemberFeedback::class, 'member_id')->latest();
    }

    public function photoChangeRequests(): HasMany
    {
        return $this->hasMany(ProfilePhotoChangeRequest::class, 'member_id')->latest();
    }
}