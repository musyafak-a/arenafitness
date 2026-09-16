<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyGuest extends Model
{
    use HasFactory;

    protected $table = 'daily_guests';

    protected $fillable = [
        'full_name',
        'phone',
        'visit_type',
        'visit_at',
    ];

    protected $casts = [
        'visit_at' => 'datetime',
    ];

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class, 'daily_guest_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'daily_guest_id');
    }
}
