<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for Midtrans API.
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
    'client_key'  => env('MIDTRANS_CLIENT_KEY', ''),
    'server_key'  => env('MIDTRANS_SERVER_KEY', ''),
    
    // false = sandbox, true = production
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Optional: enable 3D Secure for credit card transaction
    'is_3ds' => true,
];
