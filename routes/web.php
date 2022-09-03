<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\bidangVerifikatorController;
use App\Http\Controllers\perangkatDesaController;
use App\Http\Controllers\generalController;
use App\Http\Controllers\akunController;
use App\Http\Controllers\rpjmdController;
use App\Http\Controllers\rkpdController;
use App\Http\Controllers\dokumenDesaController;
use App\Http\Controllers\dokumenOpdController;
use App\Http\Controllers\pengaturanController;
use App\Http\Controllers\alokasiDesaController;
use App\Http\Controllers\makroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'do_login'])->name('do_login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/set-tahun-penganggaran', [generalController::class, 'setTahunAnggaran'])->name('set-tahun-penganggaran');
Route::get('/', [generalController::class, 'index'])->name('index');
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['check_auth:1']], function () {
        Route::get('/dashboard-admin', [DashboardController::class, 'dashboard_admin'])->name('dashboard.admin');
        
        Route::prefix('master')->group(function(){
            Route::prefix('perangkat-desa')->group(function () {
                Route::get('/', [perangkatDesaController::class, 'index'])->name('admin.perangkat_desa');
                Route::post('/store', [perangkatDesaController::class, 'store'])->name('admin.perangkat_desa.store');
                Route::get('/byParams/{params}', [perangkatDesaController::class, 'byParams'])->name('admin.perangkat_desa.byId');
                Route::post('/update/{params}', [perangkatDesaController::class, 'update'])->name('admin.perangkat_desa.update');
                Route::post('/delete/{params}', [perangkatDesaController::class, 'delete'])->name('admin.perangkat_desa.delete');
                Route::get('/datatable-list', [perangkatDesaController::class, 'datatable_list'])->name('admin.perangkat_desa.list');
            });
            Route::prefix('bidang-verifikator')->group(function () {
                Route::get('/', [bidangVerifikatorController::class, 'index'])->name('admin.bidang_verifikator');
                Route::post('/store', [bidangVerifikatorController::class, 'store'])->name('admin.bidang_verifikator.store');
                Route::get('/datatable-list', [bidangVerifikatorController::class, 'datatable_list'])->name('admin.bidang_verifikator.list');
                Route::get('/byParams/{params}', [bidangVerifikatorController::class, 'byParams'])->name('admin.bidang_verifikator.byId');
            });
            Route::prefix('akun')->group(function () {
                Route::get('/', [akunController::class, 'index'])->name('admin.akun');
                Route::post('/store', [akunController::class, 'store'])->name('admin.akun.store');
                Route::get('/datatable-list', [akunController::class, 'datatable_list'])->name('admin.akun.list');
                Route::get('/byParams/{params}', [akunController::class, 'byParams'])->name('admin.akun.byId');
                Route::post('/update/{params}', [akunController::class, 'update'])->name('admin.akun.update');
            });
        });

        Route::prefix('jadwal')->group(function () {
            Route::get('/', [pengaturanController::class, 'index']);
            Route::post('/store', [pengaturanController::class, 'store']);
            Route::get('/datatable-list', [pengaturanController::class, 'datatable_list']);
            Route::get('/byParams/{params}', [pengaturanController::class, 'byParams']);
            Route::post('/update/{params}', [pengaturanController::class, 'update']);
        });

       
       
    });
    Route::group(['middleware' => ['check_auth:2']], function () {
        Route::get('/dashboard-admin-opd', [DashboardController::class, 'dashboard_admin_opd'])->name('dashboard.admin_opd');
        Route::prefix('akun-opd')->group(function () {
            Route::prefix('dokumen')->group(function () {
                Route::get('/', [dokumenOpdController::class, 'index']);
                Route::get('/indikator-kinerja-kunci', [dokumenOpdController::class, 'ikk_view']);
                Route::get('/indikator-kinerja-kunci/datatable_iki', [dokumenOpdController::class, 'datatable_iki']);
                
                Route::get('/export_ikk', [dokumenOpdController::class, 'export_ikk']);
            });
        }); 
    });

    Route::group(['middleware' => ['check_auth:3']], function () {
        Route::get('/dashboard-admin-desa', [DashboardController::class, 'dashboard_admin_desa'])->name('dashboard.admin_desa');
        Route::prefix('akun-desa')->group(function () {
            Route::prefix('dokumen')->group(function () {
                Route::get('/', [dokumenDesaController::class, 'index']);
            });
          
            
        }); 
    });

    Route::group(['middleware' => ['check_auth:4']], function () {
        Route::get('/dashboard-admin-verifikator', [DashboardController::class, 'dashboard_admin_verifikator'])->name('dashboard.admin.verifikator');
    });
   

    Route::prefix('get-data')->group(function () {
        Route::get('/desa', [generalController::class, 'get_desa'])->name('get_data.desa');
        Route::get('/satuan', [generalController::class, 'get_satuan'])->name('get_data.satuan');
        Route::get('/pagu-desa', [generalController::class, 'pagu_desa'])->name('get_data.pagu_desa');
        Route::get('/perangkat-desa', [generalController::class, 'get_perangkat_desa'])->name('get_data.perangkat_desa');
        Route::get('/bidang', [generalController::class, 'get_bidang'])->name('get_data.bidang');
        Route::get('/unit-kerja', [generalController::class, 'get_unit_kerja'])->name('get_data.unit_kerja');
        Route::get('/master-verifikasi/{params}', [generalController::class, 'get_master_verifikasi'])->name('get_data.master_verifikasi');
        Route::get('/documentByVerifikasi', [generalController::class, 'documentByVerifikasi'])->name('get_data.documentByVerifikasi');
        Route::post('/master-verifikasi/', [generalController::class, 'master_verifikasi'])->name('get_data.master_verifikasi.store');
        Route::get('/checkJadwal', [generalController::class, 'checkJadwal'])->name('get_data.checkJadwal');
        Route::get('/dashboard', [DashboardController::class, 'get_dashboard']);
    });  

    Route::prefix('general')->group(function () {
        Route::get('/datatable-list', [generalController::class, 'datatable_list'])->name('datatable_list');
        Route::post('/storeDocuments', [generalController::class, 'storeDocuments'])->name('storeDocuments');
        Route::get('/byParams/{params}', [generalController::class, 'byParams'])->name('byParams');
        Route::post('/updateDocuments/{params}', [generalController::class, 'updateDocuments'])->name('updateDocuments');
    });  

    Route::prefix('dokumen-daerah')->group(function () {
        Route::get('/rpjmd', [rpjmdController::class, 'index'])->name('rpjmd');
        Route::get('/rkpd', [rkpdController::class, 'index'])->name('rkpd');
        Route::get('/lainnya', [rkpdController::class, 'index_'])->name('lainnya'); 
    });

    Route::prefix('dokumen-desa')->group(function () {
        Route::get('/', [dokumenDesaController::class, 'index_verifikator']);
        Route::get('/konsederan', [dokumenDesaController::class, 'konsederan']);
        // Route::get('/rkpd', [rkpdController::class, 'index'])->name('rkpd');
        // Route::get('/lainnya', [rkpdController::class, 'index_'])->name('lainnya'); 
    });

    Route::get('/verifikasi', [generalController::class, 'verifikasi']);

    Route::prefix('daftar-alokasi-desa')->group(function () {
        Route::get('/', [alokasiDesaController::class, 'index']);
        Route::get('/datatable-list', [alokasiDesaController::class, 'datatable_list']);
        Route::post('/store', [alokasiDesaController::class, 'store']);
        Route::post('/update/{params}', [alokasiDesaController::class, 'update']);
        Route::get('/byParams/{params}', [alokasiDesaController::class, 'byParams']);
    });


    Route::prefix('dokumen-skpd')->group(function () {
        Route::get('/', [dokumenOpdController::class, 'index_verifikator']);
        Route::get('/verifikasi', [dokumenOpdController::class, 'verifikasi']);
        Route::get('/konsederan', [dokumenOpdController::class, 'konsederan']);
        // Route::get('/rkpd', [rkpdController::class, 'index'])->name('rkpd');
        // Route::get('/lainnya', [rkpdController::class, 'index_'])->name('lainnya'); 
    });

    Route::prefix('alokasi-skpd')->group(function () {
        Route::get('/daftar-alokasi-skpd', [dokumenDesaController::class, 'alokasi_skpd']);
        Route::get('/data-alokasi-skpd', [dokumenDesaController::class, 'data_alokasi_desa']);
    });

  

    Route::prefix('pengaturan-akun')->group(function () {
        Route::get('/', [generalController::class, 'pengaturan_akun']);
        Route::post('/update', [generalController::class, 'set_pengaturan_akun']);
    });

    Route::prefix('kinerja-makro')->group(function () {
        Route::get('/', [makroController::class, 'index']);
        Route::post('/store', [makroController::class, 'store']);
        Route::get('/datatable-list', [makroController::class, 'datatable_list']);
        Route::get('/byParams/{params}', [makroController::class, 'byParams']);
        Route::post('/update/{params}', [makroController::class, 'update']);
    });
});

// Route::group(['middleware' => ['auth']], function () {
//     Route::prefix('dashboard')->group(['middleware' => ['check_auth:admin']], function () {
//         Route::get('/{params}', DashboardController::class);
//     });
// });


