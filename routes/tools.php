<?php

use Illuminate\Support\Facades\Route;

Route::prefix('tools')->name('tools.')->group(function () {
    // Migration Generator
    Route::prefix('migration-generator')->group(function () {
        Route::get('/', [\App\Http\Controllers\Tools\MigrationGeneratorController::class, 'index'])
            ->name('migration-generator');
        Route::post('/generate', [\App\Http\Controllers\Tools\MigrationGeneratorController::class, 'generate'])
            ->name('migration-generator.generate');
    });

    // Route untuk tools lainnya bisa ditambahkan di sini
});
