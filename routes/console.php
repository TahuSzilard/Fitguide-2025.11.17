<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('fitguide:install', function () {
    $this->call('migrate:fresh');
    $this->call('db:seed');
    $this->call('storage:link');

    $this->info('FitGuide installed!');
});
