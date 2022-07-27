<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Roll-up enabled
    |--------------------------------------------------------------------------
    |
    | This value determines whether data will be calculated and rolled up from
    | source. If this value is false, then source DB credentials are not required.
    |
    */
    'roll-up-enabled' => env('DATA_REPORTING_ROLL_UP_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for calculating and
    | retrieving reporting.
    |
    */

    'database' => [

        /*
        |--------------------------------------------------------------------------
        | Source Database
        |--------------------------------------------------------------------------
        |
        | The source database to retrieve data for calculation
        |
        */
        'source' => [
            'driver'         => 'mysql',
            'url'            => env('DATA_REPORTING_SOURCE_DATABASE_URL', env('DATABASE_URL')),
            'host'           => env('DATA_REPORTING_SOURCE_DB_HOST', env('DB_HOST')),
            'port'           => env('DATA_REPORTING_SOURCE_DB_PORT', env('DB_POST')),
            'database'       => env('DATA_REPORTING_SOURCE_DB_DATABASE', env('DB_DATABASE')),
            'username'       => env('DATA_REPORTING_SOURCE_DB_USERNAME', env('DB_USERNAME')),
            'password'       => env('DATA_REPORTING_SOURCE_DB_PASSWORD', env('DB_PASSWORD')),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'roll-up' => [
            'driver'         => 'mysql',
            'url'            => env('DATA_REPORTING_ROLLUP_DATABASE_URL', env('DATABASE_URL')),
            'host'           => env('DATA_REPORTING_ROLLUP_DB_HOST', env('DB_HOST')),
            'port'           => env('DATA_REPORTING_ROLLUP_DB_PORT', env('DB_POST')),
            'database'       => env('DATA_REPORTING_ROLLUP_DB_DATABASE', env('DB_DATABASE')),
            'username'       => env('DATA_REPORTING_ROLLUP_DB_USERNAME', env('DB_USERNAME')),
            'password'       => env('DATA_REPORTING_ROLLUP_DB_PASSWORD', env('DB_PASSWORD')),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ],

];
