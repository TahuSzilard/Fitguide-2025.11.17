<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InstallFitGuide extends Command
{
    protected $signature = 'fitguide:install';
    protected $description = 'Install and initialize FitGuide project with full DB reset, migrations and seeders';

    public function handle()
    {
        $this->info(' Resetting database...');
        Artisan::call('migrate:fresh');

        $this->info(' Seeding basic data...');
        Artisan::call('db:seed');

        $this->info(' Creating default admin user...');

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@fitguide.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info(' Installation complete! Admin login ready:');
        $this->info('    Email: admin@fitguide.com');
        $this->info('    Password: admin123');

        return Command::SUCCESS;
    }
}
