<?php

return [
    'auth' => [
        'guard' => 'officer', // Tentukan guard yang digunakan untuk autentikasi
    ],

    'pages' => [
        // Atur pengaturan halaman di sini jika diperlukan
    ],

    'panels' => [
        App\Providers\Filament\AdminPanelProvider::class,
        App\Providers\Filament\UserPanelProvider::class,
    ],

    // Pengaturan lainnya bisa ditambahkan di sini sesuai kebutuhan
];
