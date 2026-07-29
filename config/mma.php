<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Copa Índigo MMA — Configuración de pagos y notificaciones
    |--------------------------------------------------------------------------
    */

    'payments' => [
        'transferencia' => [
            'label'   => 'Transferencia Bancaria',
            'bank'    => env('PAYMENT_TRANSFER_BANK', '0105 - Mercantil C.A Banco Universal'),
            'holder'  => env('PAYMENT_TRANSFER_HOLDER', 'FUNDACION DAVID BRANDT LASABALLET'),
            'account' => env('PAYMENT_TRANSFER_ACCOUNT', '01050120211120303036'),
            'id'      => env('PAYMENT_TRANSFER_ID', 'J-50709884-2'),
        ],
        'pago_movil' => [
            'label' => 'Pago Móvil',
            'bank'  => env('PAYMENT_PM_BANK', '0102 - Banco de Venezuela'),
            'phone' => env('PAYMENT_PM_PHONE', '0412-1234567'),
            'id'    => env('PAYMENT_PM_ID', 'V-12345678'),
        ],
        'fundacion' => [
            'label'  => 'Pago Móvil — Fundación David Brandt',
            'holder' => env('PAYMENT_FUNDACION_HOLDER', 'FUNDACION DAVID BRANDT LASABALLET'),
            'bank'   => env('PAYMENT_FUNDACION_BANK', '0105 - Mercantil C.A Banco Universal'),
            'phone'  => env('PAYMENT_FUNDACION_PHONE', '0414-4008240'),
            'id'     => env('PAYMENT_FUNDACION_ID', 'J-50709884-2'),
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
