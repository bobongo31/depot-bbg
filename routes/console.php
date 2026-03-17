<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;

Schedule::command('system:backup', ['--trigger' => 'scheduled'])
    ->dailyAt('01:30')
    ->name('backup-daily');

Schedule::command('system:backups-prune')
    ->dailyAt('02:00')
    ->name('backup-prune');

Schedule::command('system:health:poll')
    ->everyFiveMinutes()
    ->evenInMaintenanceMode()
    ->name('health-poll');