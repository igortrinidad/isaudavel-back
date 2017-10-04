<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ProccessSubscriptions::class,
        \App\Console\Commands\SendClientInvoiceReminder3Days::class,
        \App\Console\Commands\SendClientInvoiceReminder1Day::class,
        \App\Console\Commands\SendCompanyInvoiceReminder3Days::class,
        \App\Console\Commands\SendCompanyInvoiceReminder1Day::class,
        \App\Console\Commands\AlterRecipesTypes::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Verifica as subscriptions, gera e envia as fatura que vencem no dia para os clientes das empresas
        $schedule->command('process:subscriptions')->dailyAt('07:54');
        $schedule->command('invoice:client-reminder-3-days')->dailyAt('07:59');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
