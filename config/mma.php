<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Copa Índigo MMA — Configuración de pagos y notificaciones
    |--------------------------------------------------------------------------
    */

    'payments' => [
        'transferencia' => [
            'label' => 'Transferencia Bancaria',
            'bank'  => env('PAYMENT_TRANSFER_BANK', 'Banco de Venezuela'),
            'holder' => env('PAYMENT_TRANSFER_HOLDER', 'Julio Brandt'),
            'account' => env('PAYMENT_TRANSFER_ACCOUNT', 'xxxx-xxxx-xxxx-xxxx'),
            'id'      => env('PAYMENT_TRANSFER_ID', 'V-12345678'),
        ],
        'pago_movil' => [
            'label' => 'Pago Móvil',
            'bank'  => env('PAYMENT_PM_BANK', '0102 - Banco de Venezuela'),
            'phone' => env('PAYMENT_PM_PHONE', '0412-1234567'),
            'id'    => env('PAYMENT_PM_ID', 'V-12345678'),
        ],
        'zelle' => [
            'label' => 'Zelle',
            'email' => env('PAYMENT_ZELLE_EMAIL', 'correo@zelle.com'),
            'name'  => env('PAYMENT_ZELLE_NAME', 'Julio Brandt'),
        ],
        'paypal' => [
            'label' => 'PayPal',
            'email' => env('PAYMENT_PAYPAL_EMAIL', 'correo@paypal.com'),
        ],
    ],

    'whatsapp' => [
        'admin_number' => env('WHATSAPP_ADMIN_NUMBER', '584242818836'),
        'notify_admin_on_register' => env('WHATSAPP_NOTIFY_ADMIN', true),
    ],
];
