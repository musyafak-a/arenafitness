<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    public const TYPE_MEMBERSHIP = 'membership_payment';
    public const TYPE_DAILY_PASS = 'daily_pass';
    public const TYPE_PRODUCT_SALE = 'product_sale';
    public const TYPE_OTHER = 'other';

    public static function typeLabel(?string $type): string
    {
        return match ($type) {
            self::TYPE_MEMBERSHIP => 'Pembayaran Membership',
            self::TYPE_DAILY_PASS => 'Daily Pass',
            self::TYPE_PRODUCT_SALE => 'Penjualan Produk',
            self::TYPE_OTHER => 'Lain-lain',
            default => ucfirst(str_replace('_', ' ', (string) $type)),
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabel($this->type);
    }

    protected $fillable = [
        'invoice',
        'member_id',
        'daily_guest_id',
        'cashier_user_id',
        'type',
        'customer_name',
        'description',
        'amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_status',
        'transaction_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_amount' => 'integer',
            'change_amount' => 'integer',
            'transaction_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function dailyGuest(): BelongsTo
    {
        return $this->belongsTo(DailyGuest::class, 'daily_guest_id');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }
}
