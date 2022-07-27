<?php

namespace Imega\DataReporting\Tests;

use Imega\DataReporting\Providers\DataReportingServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Class TestCase
 *
 * @package Imega\DataReporting\Tests
 */
class TestCase extends OrchestraTestCase
{
    use WithFaker;

    /**
     * Set up any config values we need
     *
     * @param Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {

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
