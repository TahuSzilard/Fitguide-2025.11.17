<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'discountCode',
        'expiryDate',
        'discountAmount',
        'usedOrNot',
    ];

    protected $casts = [
        'expiryDate' => 'date',
        'usedOrNot' => 'boolean',
    ];

    /** Kapcsolat a User-rel */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Automatikus generálás, ha hiányzik a kód */
    protected static function booted()
    {
        static::creating(function ($discount) {
            if (!$discount->discountCode) {
                $discount->discountCode = strtoupper(Str::random(10));
            }
        });
    }

    /** Kupon érvényesség ellenőrzése */
    public function isValid(): bool
    {
        return !$this->usedOrNot &&
            (!$this->expiryDate || $this->expiryDate->isFuture());
    }
}
