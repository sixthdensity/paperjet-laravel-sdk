<?php

namespace Paperjet\Sdk;

use Illuminate\Support\ServiceProvider;

class PaperjetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/paperjet.php', 'paperjet');

        $this->app->singleton(PaperjetManager::class, function ($app) {
            return new PaperjetManager(
                endpoint: (string) config('paperjet.endpoint'),
                apiKey: (string) config('paperjet.api_key'),
                timeout: (int) config('paperjet.timeout', 15),
                defaults: (array) config('paperjet.defaults', []),
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/paperjet.php' => config_path('paperjet.php'),
            ], 'paperjet-config');
        }
    }
}
