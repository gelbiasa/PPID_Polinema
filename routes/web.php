<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardMPUController;
use App\Http\Controllers\DashboardRespondenController;
use App\Http\Controllers\DashboardVerifikatorController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+'); // Artinya: Ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

// Group route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index'])->middleware('authorize:ADM');
    Route::get('/dashboardMPU', [DashboardMPUController::class, 'index'])->middleware('authorize:MPU');
    Route::get('/dashboardVerifikator', [DashboardVerifikatorController::class, 'index'])->middleware('authorize:VFR');
    Route::get('/dashboardResponden', [DashboardRespondenController::class, 'index'])->middleware('authorize:RPN');    
});