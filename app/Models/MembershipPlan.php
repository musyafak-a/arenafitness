<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_months',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'duration_months' => 'integer',
        'price' => 'integer',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MembershipSubscription::class, 'membership_plan_id');
    }

    public function renewalRequests(): HasMany
    {
        return $this->hasMany(MembershipRenewalRequest::class, 'membership_plan_id');
    }
}
