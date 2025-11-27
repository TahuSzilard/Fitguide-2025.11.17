<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductType;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Supplements',     'slug' => 'supplements'],
            ['name' => 'Snacks',          'slug' => 'snacks'],
            ['name' => 'Equipment',       'slug' => 'equipment'],
            ['name' => 'Clothing',        'slug' => 'clothing'],
            ['name' => 'Accessories',     'slug' => 'accessories'],
            ['name' => 'Packages & Gift', 'slug' => 'packages-gift'],
        ];

        // Válaszd EGYIKET:
        foreach ($rows as $r) {
            ProductType::updateOrCreate(
                ['slug' => $r['slug']],   // egyedi kulcs
                ['name' => $r['name']]    // frissítendő mezők
            );
        }
        // VAGY:
        // ProductType::upsert($rows, ['slug'], ['name']);
    }
}
