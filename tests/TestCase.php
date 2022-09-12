<?php

namespace Imega\DataReporting\Tests;

use Illuminate\Support\Facades\Artisan;
use Imega\DataReporting\Providers\DataReportingServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Class TestCase
 *
 * @package Imega\DataReporting\Tests
 */
class TestCase extends OrchestraTestCase
{
    /**
     * Set up any config values we need
     *
     * @param Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.connections.data-reporting-angus', config('database.connections.sqlite'));

        include_once __DIR__ . '/../tests/database/migrations/create_clients_table.php';
        include_once __DIR__ . '/../tests/database/migrations/create_audits_table.php';
        include_once __DIR__ . '/../tests/database/migrations/create_finance_providers_table.php';

        (new \CreateClientsTable)->up();
        (new \CreateAuditsTable)->up();
        (new \CreateFinanceProvidersTable)->up();
    }

    /**
     * Set up any service providers we rely on
     *
     * @param Application $app
     *
     * @return array|string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            DataReportingServiceProvider::class,
        ];
    }

    /**
     * Set up any facades we use
     *
     * @param Application $app
     *
     * @return array|string[]
     */
    protected function getPackageAliases($app): array
    {
        return [];
    }
}
