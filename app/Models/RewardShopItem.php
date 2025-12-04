<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardShopItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price_points',
        'image',
    ];
}
