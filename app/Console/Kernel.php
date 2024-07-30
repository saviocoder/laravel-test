<?php

namespace App\Console;

use App\Jobs\SendMail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('notification:send')->daily()->withoutOverlapping()->onOneServer();
        //enviando email por dia, o email nao pode executar em cima de outro email, e apenas um email para cada servidor
        
        $schedule->job(new SendMail)->everyFiveMinutes();
        //executando um job a cada 5 minutos
    }   

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
