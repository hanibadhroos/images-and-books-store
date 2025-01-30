<?php

use App\Jobs\checkHoldingRecords;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->daily();

// إضافة الجدولة
Schedule::command('notify:incomplete-records')->daily();



// Schedule::job(new checkHoldingRecords)->everyFiveMinutes();

