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
    | Client statuses
    |--------------------------------------------------------------------------
    |
    | A list of available client statuses
    |
    */
    'client-statuses' => [
        'ACTIVE'   => env('DATA_REPORTING_CLIENT_ACTIVE', 'ACTIVE'),
        'INACTIVE' => env('DATA_REPORTING_CLIENT_ACTIVE', 'INACTIVE'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Finance Providers
    |--------------------------------------------------------------------------
    |
    | A sequential array of excluded finance provider ids
    |
    */
    'excluded-finance-provider-ids' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Approved statuses
    |--------------------------------------------------------------------------
    |
    | A list of approved statuses
    |
    */
    'approved-statuses' => [
        'converted',
        'VERIFIED',
        'READY',
        'COMPLETE',
        '5',
        'signed',
        'ACCEPTED',
        'Live',
        'Order Release Verified'
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for calculating and
    | retrieving reporting.
    |
    */
    'database'        => [

        /*
        |--------------------------------------------------------------------------
        | Source Database
        |--------------------------------------------------------------------------
        |
        | The source database to retrieve data for calculation
        |
        */
        'source'  => [

            /*
            |--------------------------------------------------------------------------
            | Source Database - Angus
            |--------------------------------------------------------------------------
            |
            | Angus database to retrieve data for calculation
            |
            */
            'angus'  => [
                'driver'         => 'mysql',
                'url'            => env('DATA_REPORTING_SOURCE_ANGUS_DATABASE_URL', env('DATABASE_URL')),
                'host'           => env('DATA_REPORTING_SOURCE_ANGUS_DB_HOST', env('DB_HOST')),
                'port'           => env('DATA_REPORTING_SOURCE_ANGUS_DB_PORT', env('DB_PORT')),
                'database'       => env('DATA_REPORTING_SOURCE_ANGUS_DB_DATABASE', env('DB_DATABASE')),
                'username'       => env('DATA_REPORTING_SOURCE_ANGUS_DB_USERNAME', env('DB_USERNAME')),
                'password'       => env('DATA_REPORTING_SOURCE_ANGUS_DB_PASSWORD', env('DB_PASSWORD')),
                'charset'        => 'utf8mb4',
                'collation'      => 'utf8mb4_unicode_ci',
                'prefix'         => '',
                'prefix_indexes' => true,
                'strict'         => false,
                'engine'         => null,
                'options'        => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],

            /*
            |--------------------------------------------------------------------------
            | Source Database - Angus
            |--------------------------------------------------------------------------
            |
            | Orders database to retrieve data for calculation
            |
            */
            'orders' => [
                'driver'      => 'mysql',
                'url'         => env('DATA_REPORTING_SOURCE_ORDERS_DATABASE_URL', env('ORDERS_DATABASE_URL')),
                'host'        => env('DATA_REPORTING_SOURCE_ORDERS_DB_HOST', env('ORDERS_DB_HOST')),
                'port'        => env('DATA_REPORTING_SOURCE_ORDERS_DB_PORT', env('ORDERS_DB_PORT')),
                'database'    => env('DATA_REPORTING_SOURCE_ORDERS_DB_DATABASE', env('ORDERS_DB_DATABASE')),
                'username'    => env('DATA_REPORTING_SOURCE_ORDERS_DB_USERNAME', env('ORDERS_DB_USERNAME')),
                'password'    => env('DATA_REPORTING_SOURCE_ORDERS_DB_PASSWORD', env('ORDERS_DB_PASSWORD')),
                'unix_socket' => '',
                'charset'     => 'utf8',
                'modes'       => [],
                'prefix'      => '',
                'strict'      => true,
                'engine'      => null,
                'options'     => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Roll-Up Database
        |--------------------------------------------------------------------------
        |
        | The roll-up stats database where queries are pre-calculated for reports.
        |
        */
        'roll-up' => [
            'driver'         => 'mysql',
            'url'            => env('DATA_REPORTING_ROLLUP_DATABASE_URL', env('DATABASE_URL')),
            'host'           => env('DATA_REPORTING_ROLLUP_DB_HOST', env('DB_HOST')),
            'port'           => env('DATA_REPORTING_ROLLUP_DB_PORT', env('DB_PORT')),
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
