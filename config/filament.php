<?php

return [
    'default' => env('FILAMENT_ADMIN_PANEL', 'admin'),

    'panels' => [
        'admin' => [
            'path' => env('FILAMENT_ADMIN_PATH', 'admin'),
            'domain' => env('FILAMENT_ADMIN_DOMAIN'),
            'auth' => [
                'guard' => 'web',
                'pages' => [
                    'login' => \Filament\Pages\Auth\Login::class,
                ],
            ],
        ],
    ],
];
