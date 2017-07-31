<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserSocialProviderRepository::class, \App\Repositories\UserSocialProviderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceRepository::class, \App\Repositories\PlaceRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlacePhotoRepository::class, \App\Repositories\PlacePhotoRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceDocumentRepository::class, \App\Repositories\PlaceDocumentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceAppointmentRepository::class, \App\Repositories\PlaceAppointmentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceCalendarSettingsRepository::class, \App\Repositories\PlaceCalendarSettingsRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ClientRepository::class, \App\Repositories\ClientRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceReservationsRepository::class, \App\Repositories\PlaceReservationsRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\OracleUserRepository::class, \App\Repositories\OracleUserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\OwnerRequestRepository::class, \App\Repositories\OwnerRequestRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\OwnerRequestDocumentRepository::class, \App\Repositories\OwnerRequestDocumentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceVideoRepository::class, \App\Repositories\PlaceVideoRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceTrackingRepository::class, \App\Repositories\PlaceTrackingRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ReservationInterestRepository::class, \App\Repositories\ReservationInterestRepositoryEloquent::class);
        //:end-bindings:
    }
}
