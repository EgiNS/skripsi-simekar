<?php

use App\Livewire\ABK;
use App\Livewire\Abk\BasedDetailABK;
use App\Livewire\Abk\Detail\DetailDataABK;
use App\Livewire\ABK\DetailABK;
use App\Livewire\Abk\Formasi\Formasi;
use App\Livewire\Abk\Jabatan\UpdateNomenklatur;
use App\Livewire\Abk\Pegawai\UpdatePegawai;
use App\Livewire\Abk\Status\StatusABK;
use App\Livewire\ABK\TabelABK;
use App\Livewire\Abk\Tambah\TambahABK;
use App\Livewire\AngkaKredit\Daftar\DaftarAngkaKredit;
use App\Livewire\AngkaKredit\Upload\UploadAngkaKredit;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Karier\RekomendasiKarier;
use App\Livewire\Mutasi\Kepala\SimulasiKepala;
use App\Livewire\Mutasi\Pegawai\SimulasiPegawai;
use App\Livewire\Mutasi\Usul\UsulMutasi;
use App\Livewire\Ukom\Informasi\DetailInformasiUkom;
use App\Livewire\Ukom\Informasi\InformasiUkom;
use App\Livewire\Ukom\Informasi\TambahInformasiUkom;
use App\Livewire\Ukom\Jadwal\JadwalUkom;
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

Route::get('/', function () {
    return view('app');
});

//admin
Route::get('/status-abk', StatusABK::class)->name('status-abk');
Route::get('/detail-abk', DetailDataABK::class)->name('detail-abk');
Route::get('/tambah-abk', TambahABK::class)->name('tambah-abk');
Route::get('/update-pegawai', UpdatePegawai::class)->name('update-pegawai');
Route::get('/update-nomenklatur', UpdateNomenklatur::class)->name('update-nomenklatur');
Route::get('/simulasi-pegawai', SimulasiPegawai::class)->name('simulasi-pegawai');
Route::get('/simulasi-kepala', SimulasiKepala::class)->name('simulasi-kepala');
Route::get('/usul-mutasi', UsulMutasi::class)->name('usul-mutasi');
Route::get('/angka-kredit', DaftarAngkaKredit::class)->name('angka-kredit');
Route::get('/upload-angka-kredit', UploadAngkaKredit::class)->name('upload-angka-kredit');
Route::get('/jadwal-ukom', JadwalUkom::class)->name('jadwal-ukom');
Route::get('/info-ukom', InformasiUkom::class)->name('info-ukom');
Route::get('/tambah-info-ukom', TambahInformasiUkom::class)->name('tambah-info-ukom');
Route::get('/info-ukom/{id}', DetailInformasiUkom::class)->name('detail-info');
Route::get('/dashboard', Dashboard::class)->name('dashboard');

//user
Route::get('/formasi', Formasi::class)->name('formasi');
Route::get('/rekomendasi-karier', RekomendasiKarier::class)->name('rekomendasi-karier');