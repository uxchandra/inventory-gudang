<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanBarangMasukController;
use App\Http\Controllers\LaporanBarangKeluarController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LaporanPermintaanController;


Route::middleware('auth')->group(function () {

    Route::group(['middleware' => 'checkRole:kepala gudang'], function(){
        Route::get('/data-pengguna/get-data', [ManajemenUserController::class, 'getDataPengguna']);
        Route::get('/api/role/', [ManajemenUserController::class, 'getRole']);
        Route::resource('/data-pengguna', ManajemenUserController::class);
    
        Route::get('/hak-akses/get-data', [HakAksesController::class, 'getDataRole']);
        Route::resource('/hak-akses', HakAksesController::class);

        Route::post('/permintaan-produk/{id}/approve', [OrderController::class, 'approve']);
        Route::post('/permintaan-produk/{id}/reject', [OrderController::class, 'reject']);
    });


    Route::group(['middleware' => 'checkRole:kepala gudang,admin gudang,admin service'], function(){
        Route::get('/', [DashboardController::class, 'index']);
        Route::resource('/dashboard', DashboardController::class);

        Route::get('/permintaan-produk', [OrderController::class, 'index'])->name('permintaan-produk.index');;
        Route::get('/permintaan-produk/get-data', [OrderController::class, 'getData']); 
    });

    Route::group(['middleware' => 'checkRole:kepala gudang,admin gudang'], function(){

        Route::get('/barang/get-data', [BarangController::class, 'getDataBarang']);
        Route::resource('/barang', BarangController::class);
    
        Route::get('/jenis-barang/get-data', [JenisController::class, 'getDataJenisBarang']);
        Route::resource('/jenis-barang', JenisController::class);
    
        Route::get('/satuan-barang/get-data', [SatuanController::class, 'getDataSatuanBarang']);
        Route::resource('/satuan-barang', SatuanController::class);
      
        Route::get('/api/barang-masuk/', [BarangMasukController::class, 'getAutoCompleteData']);
        Route::get('/barang-masuk/get-data', [BarangMasukController::class, 'getDataBarangMasuk']);
        Route::get('/api/satuan/', [BarangMasukController::class, 'getSatuan']);
        Route::resource('/barang-masuk', BarangMasukController::class);
    
        Route::get('/api/barang-keluar/', [BarangKeluarController::class, 'getAutoCompleteData']);
        Route::get('/barang-keluar/get-data', [BarangKeluarController::class, 'getDataBarangKeluar']);
        Route::get('/api/satuan/', [BarangKeluarController::class, 'getSatuan']);
        Route::resource('/barang-keluar', BarangKeluarController::class);
        
        Route::get('/laporan-stok/get-data', [LaporanStokController::class, 'getData']);
        Route::get('/laporan-stok/print-stok', [LaporanStokController::class, 'printStok']);
        Route::get('/api/satuan/', [LaporanStokController::class, 'getSatuan']);
        Route::resource('/laporan-stok', LaporanStokController::class);
       
        Route::get('/laporan-barang-masuk/get-data', [LaporanBarangMasukController::class, 'getData']);
        Route::get('/laporan-barang-masuk/print-barang-masuk', [LaporanBarangMasukController::class, 'printBarangMasuk']);
        Route::resource('/laporan-barang-masuk', LaporanBarangMasukController::class);
    
        Route::get('/laporan-barang-keluar/get-data', [LaporanBarangKeluarController::class, 'getData']);
        Route::get('/laporan-barang-keluar/print-barang-keluar', [LaporanBarangKeluarController::class, 'printBarangKeluar']);
        Route::resource('/laporan-barang-keluar', LaporanBarangKeluarController::class);

        Route::post('/permintaan-produk/{id}/selesaikan', [OrderController::class, 'selesaikan']);

    });

    Route::group(['middleware' => 'checkRole:kepala gudang,admin service'], function(){ 
        Route::post('/permintaan-produk', [OrderController::class, 'store']);
        Route::delete('/permintaan-produk/{id}', [OrderController::class, 'destroy']);

        Route::get('/laporan-permintaan', [LaporanPermintaanController::class, 'index']);
        Route::get('/laporan-permintaan/get-data', [LaporanPermintaanController::class, 'getData']);
        Route::get('/laporan-permintaan/print', [LaporanPermintaanController::class, 'printPermintaan']);
    });
});

require __DIR__.'/auth.php';