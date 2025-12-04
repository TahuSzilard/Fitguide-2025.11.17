<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminDiscountSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('email', 'admin@fitguide.com')->value('id');

        if (!$adminId) return;

        DB::table('discounts')->insert([
            'user_id' => $adminId,
            'discountCode' => 'WELCOME10',
            'discountAmount' => 10,
            'expiryDate' => now()->addMonths(6),
            'usedOrNot' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
