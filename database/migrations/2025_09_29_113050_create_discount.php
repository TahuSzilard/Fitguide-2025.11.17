<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            //$table->id();
            
            // USER kapcsoló oszlop ➜ kötelező
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('discountCode')->unique();
            $table->integer('discountAmount');
            $table->date('expiryDate')->nullable();
            $table->boolean('usedOrNot')->default(false);

            $table->timestamps(); // <-- most már biztosan létezik created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
