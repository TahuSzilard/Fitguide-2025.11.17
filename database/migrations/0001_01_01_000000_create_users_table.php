<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // 🔥 User role (admin / user)
            $table->enum('role', ['user', 'admin'])->default('user');

            // 🔥 ÚJ PROFIL MEZŐK
            $table->string('profile_picture')->nullable();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();

            // ⭐ Pontok a Reward rendszerhez
            $table->integer('points')->default(0);

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // ✔ AUTOMATIKUS ADMIN FIÓK
        DB::table('users')->insert([
            'name'              => 'Admin',
            'email'             => 'admin@fitguide.com',
            'password'          => Hash::make('admin123'),
            'role'              => 'admin',
            'email_verified_at' => now(),
            'profile_picture'   => null,
            'phone'             => null,
            'dob'               => null,
            'gender'            => null,
            'points'            => 0,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
