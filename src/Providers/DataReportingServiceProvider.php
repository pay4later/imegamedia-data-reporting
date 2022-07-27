<?php

namespace Imega\DataReporting\Providers;

use AuditRepositoryContract;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Imega\DataReporting\Models\Audit;

/**
 * Class DataReportingServiceProvider
 *
 * @package Imega\DataReporting\Providers
 */
final class DataReportingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPublishing();
        $this->setConnection();
    }

    /**
     * Register the service provider functionality
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/data-reporting.php', 'data-reporting');

        $this->app->bind(AuditRepositoryContract::class, Audit::class);
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing(): void
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/data-reporting.php' => config_path('data-reporting.php')
            ], 'data-reporting-config');
        }
    }

    /**
     * Sets the database connections used within the package.
     *
     * @return void
     */
    private function setConnection(): void
    {
        Config::set('database.connections.data-reporting-source', Config::get('data-reporting.database.source'));
        Config::set('database.connections.data-reporting-roll-up', Config::get('data-reporting.database.roll-up'));
    }
}
