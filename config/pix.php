<?php

return [
    'manual_enabled' => env('MANUAL_PAYMENT_MODE', false),
    'manual_key' => env('PIX_KEY', ''),
    'manual_type' => env('PIX_BANK', ''),
    'manual_name' => env('PIX_BENEFICIARY', ''),
    'manual_city' => env('PIX_BENEFICIARY_CITY', ''),
];
