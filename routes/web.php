<?php

use App\Http\Controllers\PermintaanProdukController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('/dashboard', DashboardController::class);

Route::get('/data-pengguna/get-data', [ManajemenUserController::class, 'getDataPengguna']);
Route::get('/api/role/', [ManajemenUserController::class, 'getRole']);
Route::resource('/data-pengguna', ManajemenUserController::class);

Route::get('/hak-akses/get-data', [HakAksesController::class, 'getDataRole']);
Route::resource('/hak-akses', HakAksesController::class);

Route::get('/satuan-barang/get-data', [SatuanController::class, 'getDataSatuanBarang']);
Route::resource('/satuan-barang', SatuanController::class);

Route::get('/jenis-barang/get-data', [JenisController::class, 'getDataJenisBarang']);
Route::resource('/jenis-barang', JenisController::class);

Route::get('/barang/get-data', [BarangController::class, 'getDataBarang']);
Route::resource('/barang', BarangController::class);

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

Route::resource('permintaan-produk', PermintaanProdukController::class);
Route::resource('laporan-barang-keluar', LaporanBarangKeluarController::class);
Route::resource('hak-akses', HakAksesController::class);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';