<?php

namespace WessamA\BinLookup\Providers;

use Illuminate\Support\ServiceProvider;

class BinLookupServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/binlookup.php' => config_path('binlookup.php'),
        ], 'config');
    }

    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/binlookup.php', 'binlookup'
        );
    }
}
