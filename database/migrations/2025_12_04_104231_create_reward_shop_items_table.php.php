<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('required_points')->default(0);
            $table->string('image')->nullable(); // később jöhet kép is
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_shop_items');
    }
};
