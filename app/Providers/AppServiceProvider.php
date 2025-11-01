<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->singleton(\App\Services\Notifications\JustificacionNotifier::class, function ($app) {
            $observers = collect(config('notifications.justificacion.observers', []))
                ->filter(fn ($observer) => class_exists($observer))
                ->map(fn ($observer) => $app->make($observer));

            return new \App\Services\Notifications\JustificacionNotifier($observers);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
