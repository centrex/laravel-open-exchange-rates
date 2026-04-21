<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Open Exchange Rates App ID
    |--------------------------------------------------------------------------
    */
    'app_id' => env('OPEN_EXCHANGE_RATES_APP_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Base Currency
    |--------------------------------------------------------------------------
    */
    'default_base_currency' => env('OPEN_EXCHANGE_RATES_BASE', 'USD'),


    /*
    |--------------------------------------------------------------------------
    | Currencies to Fetch
    |--------------------------------------------------------------------------
    | Comma-separated list of currency codes to fetch. Leave empty to fetch all.
    */
    'currencies' => env('OPEN_EXCHANGE_RATES_CURRENCIES', ''),

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    | Leave null to use the application default connection.
    */
    'db_connection' => env('OPEN_EXCHANGE_RATES_DB_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Table Name Prefix
    |--------------------------------------------------------------------------
    */
    'table_prefix' => env('OPEN_EXCHANGE_RATES_TABLE_PREFIX', 'oer_'),

];
