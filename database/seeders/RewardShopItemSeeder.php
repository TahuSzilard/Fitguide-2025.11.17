<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RewardShopItem;

class RewardShopItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'FitGuide Shaker Bottle',
                'description' => 'Stylish protein shaker',
                'price_points' => 800,
                'image' => 'rewards/shaker.png'
            ],
            [
                'name' => 'Protein Bar',
                'description' => 'Chocolate whey bar',
                'price_points' => 250,
                'image' => 'rewards/protein-bar.png'
            ],
            [
                'name' => '10% Discount Coupon',
                'description' => 'Use on your next purchase',
                'price_points' => 500,
                'image' => 'rewards/discount10.png'
            ]
        ];

        foreach ($items as $item) {
            RewardShopItem::create($item);
        }
    }
}
