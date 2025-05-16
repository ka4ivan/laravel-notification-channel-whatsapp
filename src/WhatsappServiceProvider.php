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

                // TODO Добавити конфіги
                return new Whatsapp([
                    'accessToken' => config('services.whatsapp.accessToken'),
                    'numberId' => config('services.whatsapp.numberId'),
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
