<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tools\MigrationGeneratorController;

Route::get('/', function () {
    return view('index');
});

Route::prefix('tools/migration-generator')->group(function () {
    Route::get('/', [MigrationGeneratorController::class, 'index'])->name('tools.migration-generator');
    Route::post('/generate', [MigrationGeneratorController::class, 'generate']);
});
