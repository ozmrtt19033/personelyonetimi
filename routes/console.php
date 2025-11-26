<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// "rapor:gonder" komutunu her Pazartesi saat 09:00'da çalıştır.
Schedule::command('rapor:gonder')
    ->weeklyOn(1, '09:00')
    ->timezone('Europe/Istanbul');

//Schedule::command('rapor:gonder')->everyMinute(); //her dakika çalışması için gerekli olan kodlama:::
