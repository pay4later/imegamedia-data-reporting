<?php

namespace Imega\DataReporting\Providers;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Imega\DataReporting\Console\LiveClients;
use Imega\DataReporting\Console\HourlyReports;

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
        if ($this->app->runningInConsole()) {
            $this->commands([
                LiveClients::class,
                HourlyReports::class,
            ]);
        }

        $this->registerPublishing();
        $this->setConnections();
        $this->registerRoutes();
    }

    /**
     * Register the service provider functionality
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/data-reporting.php', 'data-reporting');
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
                __DIR__ . '/../config/data-reporting.php' => config_path('data-reporting.php'),
            ], 'data-reporting-config');
        }
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    private function routeConfiguration(): array
    {
        return [
            'prefix'     => config('data-reporting.prefix'),
            'middleware' => config('data-reporting.middleware'),
        ];
    }

    /**
     * Sets the database connections used within the package.
     *
     * @return void
     */
    private function setConnections(): void
    {
        Config::set('database.connections.data-reporting-angus', Config::get('data-reporting.database.source.angus'));
        Config::set('database.connections.data-reporting-orders', Config::get('data-reporting.database.source.orders'));
        Config::set('database.connections.data-reporting-roll-up', Config::get('data-reporting.database.roll-up'));
    }
}
