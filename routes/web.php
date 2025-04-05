<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tools\MigrationGeneratorController;
use App\Http\Controllers\Ai\AIAsistenController;

Route::get('/', function () {
    return view('index');
});

Route::prefix('tools/migration-generator')->group(function () {
    Route::get('/', [MigrationGeneratorController::class, 'index'])->name('tools.migration-generator');
    Route::post('/generate', [MigrationGeneratorController::class, 'generate']);
});

Route::prefix('ai-asisten')->group(function () {
    Route::get('/', function () {
        return view('tools.ai-asisten');
    })->name('ai-asisten.index');
    Route::post('/llama', [AIAsistenController::class, 'llamaAssistant']);
});
