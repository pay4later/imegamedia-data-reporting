<?php

namespace Imega\DataReporting\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Imega\DataReporting\Console\DailyReports;
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
                DailyReports::class,
                HourlyReports::class,
            ]);
        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerPublishing();
        $this->setConnections();

        $this->app->booted(function () {
            if (config('data-reporting.roll-up-enabled')) {
                /** @var Schedule $schedule */
                $schedule = $this->app->make(Schedule::class);
                $schedule->command(DailyReports::class)->daily();
                $schedule->command(HourlyReports::class)->hourly();
            }
        });
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
