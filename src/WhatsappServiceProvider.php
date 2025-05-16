<?php

namespace NotificationChannels\Whatsapp;

use Illuminate\Support\ServiceProvider;

class WhatsappServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(WhatsappChannel::class)
            ->needs(Whatsapp::class)
            ->give(static function () {

                return new Whatsapp([
                    'accessToken' => config('services.whatsapp.access_token'),
                    'numberId' => config('services.whatsapp.number_id'),
                    'apiVersion' => config('services.whatsapp.api_version', '22.0'),
                ]);
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
