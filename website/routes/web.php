<?php

use App\Http\Controllers\AiChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KelolaNotifikasiController;
use App\Http\Controllers\KelolaSensorController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api-tester', [HomeController::class, 'indexApiTester'])->name('api-tester');
Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
    Route::get('dashboard/live', [DashboardController::class, 'live'])->name('admin.dashboard.live');

    // ── Monitoring Sensor ───────────────────────────────────────
    Route::get('monitor/sensor', [MonitorController::class, 'indexSensor'])->name('admin.monitor.sensor.index');
    Route::get('monitor/sensor/live', [MonitorController::class, 'liveSensor'])
        ->name('admin.monitor.sensor.live');
        
    // ── Monitoring Aktuator ───────────────────────────────────────
    Route::get('/monitor/akuator', [MonitorController::class, 'indexAkuator'])
    ->name('admin.monitor.akuator.index');
    Route::get('/monitor/akuator/live', [MonitorController::class, 'liveAkuator'])
    ->name('admin.monitor.akuator.live');
    
    Route::post('/aktuator-komponen/{komponen}/toggle', [MonitorController::class, 'toggleAkuator'])
    ->name('admin.komponen.toggle');
    
    // ── Kelola sensor ───────────────────────────────────────
    Route::resource('kelola-sensor', KelolaSensorController::class);
    Route::patch('/{komponen}/toggle-status',[KelolaSensorController::class, 'toggleStatus'])->name('kelola-sensor.toggle-status');

    // ── Kelola notifikasi ───────────────────────────────────
    Route::resource('kelola-notifikasi', KelolaNotifikasiController::class);

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan.index');
    Route::patch('/pengaturan', [PengaturanController::class, 'update'])->name('admin.pengaturan.update');

    Route::post('/notifications/read-all', [DashboardController::class, 'markAllRead'])->name('admin.notifications.readAll');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
