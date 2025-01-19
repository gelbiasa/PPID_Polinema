<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarPermohonanController;
use App\Http\Controllers\DaftarPertanyaanController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardMPUController;
use App\Http\Controllers\DashboardRespondenController;
use App\Http\Controllers\DashboardVerifikatorController;
use App\Http\Controllers\HasilPermohonanController;
use App\Http\Controllers\HasilPertanyaanController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\NotifikasiControllerADM;
use App\Http\Controllers\NotifikasiControllerMPU;
use App\Http\Controllers\NotifikasiControllerVFR;
use App\Http\Controllers\PengajuanPermohonanController;
use App\Http\Controllers\PengajuanPertanyaanController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister']);

// Group route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index'])->middleware('authorize:ADM');
    Route::get('/dashboardMPU', [DashboardMPUController::class, 'index'])->middleware('authorize:MPU');
    Route::get('/dashboardVFR', [DashboardVerifikatorController::class, 'index'])->middleware('authorize:VFR');
    Route::get('/dashboardResponden', [DashboardRespondenController::class, 'index'])->middleware('authorize:RPN');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::post('/update_profile', [ProfileController::class, 'update_profile']);
        Route::delete('/delete_profile', [ProfileController::class, 'delete_profile']);
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

    Route::group(['prefix' => 'notifMPU', 'middleware' => ['authorize:MPU']], function () {
        Route::get('/', [NotifikasiControllerMPU::class, 'index']);
        Route::get('/notif_permohonan', [NotifikasiControllerMPU::class, 'notifikasiPermohonan']);
        Route::get('/notif_pertanyaan', [NotifikasiControllerMPU::class, 'notifikasiPertanyaan']);
        Route::post('/tandai-dibaca/{id}', [NotifikasiControllerMPU::class, 'tandaiDibaca'])->name('notifAdmin.tandaiDibaca');
        Route::delete('/hapus/{id}', [NotifikasiControllerMPU::class, 'hapusNotifikasi'])->name('notifAdmin.hapus');
    });

    Route::group(['prefix' => 'daftarPermohonan', 'middleware' => ['authorize:VFR']], function () {
        Route::get('/', [DaftarPermohonanController::class, 'index']);
        Route::get('/daftarAkademik', [DaftarPermohonanController::class, 'daftarAkademik']);
        Route::post('/akademik/setujui/{id}', [DaftarPermohonanController::class, 'setujuiPermohonanAkademik'])->name('daftarPermohonan.akademik.setujui');
        Route::post('/akademik/tolak/{id}', [DaftarPermohonanController::class, 'tolakPermohonanAkademik'])->name('daftarPermohonan.akademik.tolak');
        Route::post('/akademik/hapus/{id}', [DaftarPermohonanController::class, 'hapusPermohonanAkademik'])->name('daftarPermohonan.akademik.hapus');
        Route::get('/daftarLayanan', [DaftarPermohonanController::class, 'daftarLayanan']);
        Route::post('/layanan/setujui/{id}', [DaftarPermohonanController::class, 'setujuiPermohonanLayanan'])->name('daftarPermohonan.layanan.setujui');
        Route::post('/layanan/tolak/{id}', [DaftarPermohonanController::class, 'tolakPermohonanLayanan'])->name('daftarPermohonan.layanan.tolak');
        Route::post('/layanan/hapus/{id}', [DaftarPermohonanController::class, 'hapusPermohonanLayanan'])->name('daftarPermohonan.layanan.hapus');
        Route::get('/daftarTeknis', [DaftarPermohonanController::class, 'daftarTeknis']);
        Route::post('/teknis/setujui/{id}', [DaftarPermohonanController::class, 'setujuiPermohonanTeknis'])->name('daftarPermohonan.teknis.setujui');
        Route::post('/teknis/tolak/{id}', [DaftarPermohonanController::class, 'tolakPermohonanTeknis'])->name('daftarPermohonan.teknis.tolak');
        Route::post('/teknis/hapus/{id}', [DaftarPermohonanController::class, 'hapusPermohonanTeknis'])->name('daftarPermohonan.teknis.hapus');
    });

    Route::group(['prefix' => 'daftarPertanyaan', 'middleware' => ['authorize:VFR']], function () {
        Route::get('/', [DaftarPertanyaanController::class, 'index']);
        Route::get('/daftarAkademik', [DaftarPertanyaanController::class, 'daftarAkademik']);
        Route::post('/akademik/setujui/{id}', [DaftarPertanyaanController::class, 'setujuiPertanyaanAkademik']);
        Route::post('/akademik/tolak/{id}', [DaftarPertanyaanController::class, 'tolakPertanyaanAkademik']);
        Route::post('/akademik/hapus/{id}', [DaftarPertanyaanController::class, 'hapusPertanyaanAkademik']);
        Route::get('/daftarLayanan', [DaftarPertanyaanController::class, 'daftarLayanan']);
        Route::post('/layanan/setujui/{id}', [DaftarPertanyaanController::class, 'setujuiPertanyaanLayanan']);
        Route::post('/layanan/tolak/{id}', [DaftarPertanyaanController::class, 'tolakPertanyaanLayanan']);
        Route::post('/layanan/hapus/{id}', [DaftarPertanyaanController::class, 'hapusPertanyaanLayanan']);
        Route::get('/daftarTeknis', [DaftarPertanyaanController::class, 'daftarTeknis']);
        Route::post('/teknis/setujui/{id}', [DaftarPertanyaanController::class, 'setujuiPertanyaanTeknis']);
        Route::post('/teknis/tolak/{id}', [DaftarPertanyaanController::class, 'tolakPertanyaanTeknis']);
        Route::post('/teknis/hapus/{id}', [DaftarPertanyaanController::class, 'hapusPertanyaanTeknis']);
    });

    Route::group(['prefix' => 'pengajuanPermohonan', 'middleware' => ['authorize:MPU']], function () {
        Route::get('/', [PengajuanPermohonanController::class, 'index']);
        Route::get('/daftarAkademik', [PengajuanPermohonanController::class, 'daftarAkademik']);
        Route::post('/akademik/setujui/{id}', [PengajuanPermohonanController::class, 'setujuiPermohonanAkademik']);
        Route::post('/tolak/{id}', [PengajuanPermohonanController::class, 'tolakPermohonanAkademik'])->name('pengajuanPermohonan.tolak');
        Route::post('/hapus/{id}', [PengajuanPermohonanController::class, 'hapusPermohonanAkademik'])->name('pengajuanPermohonan.hapus');
        Route::get('/daftarLayanan', [PengajuanPermohonanController::class, 'daftarLayanan']);
        Route::post('/layanan/setujui/{id}', [PengajuanPermohonanController::class, 'setujuiPermohonanLayanan']);
        Route::post('/tolak/{id}', [PengajuanPermohonanController::class, 'tolakPermohonanLayanan'])->name('pengajuanPermohonan.tolak');
        Route::post('/hapus/{id}', [PengajuanPermohonanController::class, 'hapusPermohonanLayanan'])->name('pengajuanPermohonan.hapus');
        Route::get('/daftarTeknis', [PengajuanPermohonanController::class, 'daftarTeknis']);
        Route::post('/teknis/setujui/{id}', [PengajuanPermohonanController::class, 'setujuiPermohonanTeknis']);
        Route::post('/tolak/{id}', [PengajuanPermohonanController::class, 'tolakPermohonanTeknis'])->name('pengajuanPermohonan.tolak');
        Route::post('/hapus/{id}', [PengajuanPermohonanController::class, 'hapusPermohonanTeknis'])->name('pengajuanPermohonan.hapus');
    }); 

    Route::group(['prefix' => 'pengajuanPertanyaan', 'middleware' => ['authorize:MPU']], function () {
        Route::get('/', [PengajuanPertanyaanController::class, 'index']);
        Route::get('/daftarAkademik', [PengajuanPertanyaanController::class, 'daftarAkademik']);
        Route::post('/akademik/setujui/{id}', [PengajuanPertanyaanController::class, 'setujuiPertanyaanAkademik']);
        Route::post('/akademik/tolak/{id}', [PengajuanPertanyaanController::class, 'tolakPertanyaanAkademik']);
        Route::post('/akademik/hapus/{id}', [PengajuanPertanyaanController::class, 'hapusPertanyaanAkademik']);
        Route::get('/daftarLayanan', [PengajuanPertanyaanController::class, 'daftarLayanan']);
        Route::post('/layanan/setujui/{id}', [PengajuanPertanyaanController::class, 'setujuiPertanyaanLayanan']);
        Route::post('/layanan/tolak/{id}', [PengajuanPertanyaanController::class, 'tolakPertanyaanLayanan']);
        Route::post('/layanan/hapus/{id}', [PengajuanPertanyaanController::class, 'hapusPertanyaanLayanan']);
        Route::get('/daftarTeknis', [PengajuanPertanyaanController::class, 'daftarTeknis']);
        Route::post('/teknis/setujui/{id}', [PengajuanPertanyaanController::class, 'setujuiPertanyaanTeknis']);
        Route::post('/teknis/tolak/{id}', [PengajuanPertanyaanController::class, 'tolakPertanyaanTeknis']);
        Route::post('/teknis/hapus/{id}', [PengajuanPertanyaanController::class, 'hapusPertanyaanTeknis']);
    });

    Route::group(['prefix' => 'hasilPermohonan', 'middleware' => ['authorize:RPN']], function () {
        Route::get('/', [HasilPermohonanController::class, 'index']);
        Route::get('/daftarAkademik', [HasilPermohonanController::class, 'daftarAkademik']);
        Route::get('/daftarLayanan', [HasilPermohonanController::class, 'daftarLayanan']);
        Route::get('/daftarTeknis', [HasilPermohonanController::class, 'daftarTeknis']);
        Route::post('/tandai-dibaca/{id}', [HasilPermohonanController::class, 'tandaiDibaca'])->name('hasilPermohonan.tandaiDibaca');
    }); 

    Route::group(['prefix' => 'hasilPertanyaan', 'middleware' => ['authorize:RPN']], function () {
        Route::get('/', [HasilPertanyaanController::class, 'index']);
        Route::get('/daftarAkademik', [HasilPertanyaanController::class, 'daftarAkademik']);
        Route::get('/daftarLayanan', [HasilPertanyaanController::class, 'daftarLayanan']);
        Route::get('/daftarTeknis', [HasilPertanyaanController::class, 'daftarTeknis']);
        Route::post('/tandai-dibaca/{id}', [HasilPertanyaanController::class, 'tandaiDibaca'])->name('hasilPertanyaan.tandaiDibaca');
    });
    
    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [LevelController::class, 'index']);         
        Route::post('/list', [LevelController::class, 'list']);     
    });

    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [UserController::class, 'index']);         // menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']);     // menampilkan data user dalam bentuk json untuk datables
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);       // menampilkan detail user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk menghapus data user Ajax
    });
});
