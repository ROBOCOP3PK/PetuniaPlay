<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mercado Pago Access Token
    |--------------------------------------------------------------------------
    |
    | Tu Access Token de Mercado Pago. Usa las credenciales de TEST para
    | desarrollo y las de PRODUCCIÓN cuando vayas a lanzar la app.
    |
    */
    'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Mercado Pago Public Key
    |--------------------------------------------------------------------------
    |
    | Tu Public Key de Mercado Pago para el frontend.
    |
    */
    'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Test Mode
    |--------------------------------------------------------------------------
    |
    | Define si estás en modo de pruebas (sandbox) o producción.
    |
    */
    'test_mode' => env('MERCADOPAGO_TEST_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | URLs de Retorno
    |--------------------------------------------------------------------------
    |
    | URLs donde Mercado Pago redirigirá al usuario después del pago.
    |
    */
    'urls' => [
        'success' => env('FRONTEND_URL') . '/payment/success',
        'failure' => env('FRONTEND_URL') . '/payment/failure',
        'pending' => env('FRONTEND_URL') . '/payment/pending',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification URL
    |--------------------------------------------------------------------------
    |
    | URL donde Mercado Pago enviará las notificaciones de cambio de estado.
    |
    */
    'notification_url' => env('APP_URL') . '/api/payments/webhook',
];
