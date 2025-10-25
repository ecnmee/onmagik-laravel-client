<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ON Platform Configuration
    |--------------------------------------------------------------------------
    */

    'api_url' => env('ON_API_URL', 'https://on.test/api'),
    
    'api_key' => env('ON_API_KEY'),
    
    'bearer_token' => env('ON_BEARER_TOKEN'),
    
    'site_id' => env('ON_SITE_ID'),
    
    'timeout' => env('ON_TIMEOUT', 30),
    
    'verify_ssl' => env('ON_VERIFY_SSL', false),
    
    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    
    'cache' => [
        'enabled' => env('ON_CACHE_ENABLED', true),
        'ttl' => env('ON_CACHE_TTL', 3600), // 1 hour
        'prefix' => env('ON_CACHE_PREFIX', 'on'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Packages Configuration
    |--------------------------------------------------------------------------
    */
    
    'packages' => [
        'on-move' => env('ON_PACKAGE_MOVE', false),
        'on-blog' => env('ON_PACKAGE_BLOG', false),
        'on-analytics' => env('ON_PACKAGE_ANALYTICS', false),
        'on-pay' => env('ON_PACKAGE_PAY', false),
    ],

];