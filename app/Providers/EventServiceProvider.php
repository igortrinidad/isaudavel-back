<?php

namespace App\Providers;

use App\Events\ClientActivity;
use App\Events\ClientNotification;
use App\Events\CompanyNotification;
use App\Events\ProfessionalNotification;
use App\Listeners\CreateClientNotification;
use App\Listeners\CreateCompanyNotification;
use App\Listeners\CreateProfissionalNotification;
use App\Listeners\IncrementClientTotalXp;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ClientActivity::class => [
            IncrementClientTotalXp::class
        ],

        ClientNotification::class => [
            CreateClientNotification::class,
        ],

        ProfessionalNotification::class => [
            CreateProfissionalNotification::class
        ],

        CompanyNotification::class => [
            CreateCompanyNotification::class
        ],


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
