<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:sync-device-status')->everyTenSeconds();
Schedule::command('app:sync-device-alert')->everyTenSeconds();
