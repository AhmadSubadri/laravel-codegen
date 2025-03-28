<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tools\MigrationGeneratorController;

Route::prefix('tools')->name('tools.')->group(function () {
    Route::prefix('migration-generator')->name('migration-generator.')->group(function () {
        Route::get('/', [MigrationGeneratorController::class, 'index'])->name('index');
        Route::post('/generate', [MigrationGeneratorController::class, 'generate'])->name('generate');
    });
});
