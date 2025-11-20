<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount', function (Blueprint $table) {
            $table->id();

            $table->string('discountCode')->unique();
            $table->date('expiryDate')->nullable();
            $table->integer('discountAmount');
            $table->boolean('usedOrNot')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount');
    }
};
