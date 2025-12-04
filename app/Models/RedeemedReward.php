<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemedReward extends Model
{
    protected $fillable = [
        'user_id',
        'reward_shop_item_id',
        'points_spent',
    ];

    public function item()
    {
        return $this->belongsTo(RewardShopItem::class, 'reward_shop_item_id');
    }
}
