<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Tambahkan custom command di sini
    ];

    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        // Menjalankan command setiap hari pukul 00:05
        $schedule->command('tables:update-status')->dailyAt('00:05');
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
