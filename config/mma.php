<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Copa Índigo MMA — Configuración de pagos y notificaciones
    |--------------------------------------------------------------------------
    */

    'payments' => [
        'transferencia' => [
            'label'   => 'Transferencia Mercantil',
            'bank'    => env('PAYMENT_TRANSFER_BANK', '0105 - Mercantil C.A Banco Universal'),
            'holder'  => env('PAYMENT_TRANSFER_HOLDER', 'FUNDACION DAVID BRANDT LASABALLETT'),
            'account' => env('PAYMENT_TRANSFER_ACCOUNT', '01050120211120303036'),
            'id'      => env('PAYMENT_TRANSFER_ID', 'J-50709884-2'),
        ],
        'pago_movil' => [
            'label' => 'Pago Móvil Mercantil',
            'bank'  => env('PAYMENT_PM_BANK', '0105 - Mercantil C.A Banco Universal'),
            'holder' => env('PAYMENT_PM_HOLDER', 'FUNDACIÓN DAVID BRANDT LASABALLETT'),
            'phone' => env('PAYMENT_PM_PHONE', '0414-4008240'),
            'id'    => env('PAYMENT_PM_ID', 'J-50709884-2'),
        ],
    ],

    'whatsapp' => [
        'admin_number' => env('WHATSAPP_ADMIN_NUMBER', '584242818836'),
        'notify_admin_on_register' => env('WHATSAPP_NOTIFY_ADMIN', true),
    ],
];
