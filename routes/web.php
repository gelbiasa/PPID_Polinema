<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarPermohonanController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardMPUController;
use App\Http\Controllers\DashboardRespondenController;
use App\Http\Controllers\DashboardVerifikatorController;
use App\Http\Controllers\NotifikasiControllerADM;
use App\Http\Controllers\NotifikasiControllerVFR;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/dashboardVFR', [DashboardVerifikatorController::class, 'index'])->middleware('authorize:VFR');
    Route::get('/dashboardResponden', [DashboardRespondenController::class, 'index'])->middleware('authorize:RPN');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::put('/update_pengguna/{id}', [ProfileController::class, 'update_pengguna']);
        Route::put('/update_password/{id}', [ProfileController::class, 'update_password']);
    });

    Route::group(['prefix' => 'permohonan', 'middleware' => ['authorize:RPN']], function () {
        Route::get('/', [PermohonanController::class, 'index']);
        // Akademik
        Route::get('/formAkademik', [PermohonanController::class, 'formAkademik']);
        Route::post('/storeAkademik', [PermohonanController::class, 'storeFormAkademik']);
        // Layanan
        Route::get('/formLayanan', [PermohonanController::class, 'formLayanan']);
        Route::post('/storeLayanan', [PermohonanController::class, 'storeFormLayanan']);
        // Teknis
        Route::get('/formTeknis', [PermohonanController::class, 'formTeknis']);
        Route::post('/storeTeknis', [PermohonanController::class, 'storeFormTeknis']);
    });

    Route::group(['prefix' => 'pertanyaan', 'middleware' => ['authorize:RPN']], function () {
        Route::get('/', [PertanyaanController::class, 'index']);
        // Akademik
        Route::get('/formAkademik', [PertanyaanController::class, 'formAkademik']);
        Route::post('/storeAkademik', [PertanyaanController::class, 'storeFormAkademik']);
        // Layanan
        Route::get('/formLayanan', [PertanyaanController::class, 'formLayanan']);
        Route::post('/storeLayanan', [PertanyaanController::class, 'storeFormLayanan']);
        // Teknis
        Route::get('/formTeknis', [PertanyaanController::class, 'formTeknis']);
        Route::post('/storeTeknis', [PertanyaanController::class, 'storeFormTeknis']);
    });

    Route::group(['prefix' => 'notifikasi', 'middleware' => ['authorize:VFR']], function () {
        Route::get('/', [NotifikasiControllerVFR::class, 'index']);
        Route::get('/notif_permohonan', [NotifikasiControllerVFR::class, 'notifikasiPermohonan']);
        Route::get('/notif_pertanyaan', [NotifikasiControllerVFR::class, 'notifikasiPertanyaan']);
        Route::post('/tandai-dibaca/{id}', [NotifikasiControllerVFR::class, 'tandaiDibaca'])->name('notifikasi.tandaiDibaca');
        Route::delete('/hapus/{id}', [NotifikasiControllerVFR::class, 'hapusNotifikasi'])->name('notifikasi.hapus');
    });

    Route::group(['prefix' => 'notifAdmin', 'middleware' => ['authorize:ADM']], function () {
        Route::get('/', [NotifikasiControllerADM::class, 'index']);
        Route::get('/notif_permohonan', [NotifikasiControllerADM::class, 'notifikasiPermohonan']);
        Route::get('/notif_pertanyaan', [NotifikasiControllerADM::class, 'notifikasiPertanyaan']);
        Route::post('/tandai-dibaca/{id}', [NotifikasiControllerADM::class, 'tandaiDibaca'])->name('notifAdmin.tandaiDibaca');
        Route::delete('/hapus/{id}', [NotifikasiControllerADM::class, 'hapusNotifikasi'])->name('notifAdmin.hapus');
    });

    Route::group(['prefix' => 'daftarPermohonan', 'middleware' => ['authorize:VFR']], function () {
        Route::get('/', [DaftarPermohonanController::class, 'index']);
        // Akademik
        Route::get('/daftarAkademik', [DaftarPermohonanController::class, 'daftarAkademik']);
        Route::post('/storeAkademik', [PermohonanController::class, 'storeFormAkademik']);
        // Layanan
        Route::get('/formLayanan', [PermohonanController::class, 'formLayanan']);
        Route::post('/storeLayanan', [PermohonanController::class, 'storeFormLayanan']);
        // Teknis
        Route::get('/formTeknis', [PermohonanController::class, 'formTeknis']);
        Route::post('/storeTeknis', [PermohonanController::class, 'storeFormTeknis']);
    });
});
