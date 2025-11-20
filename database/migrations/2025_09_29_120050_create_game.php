<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();

            $table->integer('score')->default(0);
            $table->integer('highScore')->default(0);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game');
    }
};
