<?php

use App\Http\Controllers\ApiAktuatorStatusController;
use App\Http\Controllers\ApiBatchLogController;
use App\Http\Controllers\ApiSensorLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('sensor-log/store', [ApiSensorLogController::class, 'store'])
    ->name('api.sensor-log.store');
Route::post('batch-log/store', [ApiBatchLogController::class, 'store'])
    ->name('api.batch-log.store');

Route::get('aktuator/status', [ApiAktuatorStatusController::class, 'status'])
->name('api.aktuator.status');