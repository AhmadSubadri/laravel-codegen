<?php
require __DIR__ . '/tools.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tools\MigrationGeneratorController;

Route::get('/', function () {
    return view('welcome');
});
Route::post(
    '/tools/migration-generator/generate',
    [MigrationGeneratorController::class, 'generate']
)
    ->name('tools.migration-generator.generate');
