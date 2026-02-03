<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule para processar notificações agendadas a cada minuto
Schedule::command('notifications:process-scheduled')->everyThirtyMinutes()->withoutOverlapping();
