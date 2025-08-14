<?php

use App\Http\Controllers\ProfileController;
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
use App\Livewire\AngkaKredit\Buat\BuatPAK;
use App\Livewire\AngkaKredit\Buat\HasilPAK;
use App\Livewire\AngkaKredit\Daftar\DaftarAngkaKredit;
use App\Livewire\AngkaKredit\Daftar\EksporPak;
use App\Livewire\AngkaKredit\Kinerja\UpdateKinerja;
use App\Livewire\AngkaKredit\Pegawai\AngkaKredit;
use App\Livewire\AngkaKredit\Upload\UploadAngkaKredit;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\DashboardPegawai;
use App\Livewire\Karier\AdminRekomKarier;
use App\Livewire\Karier\EditRekomKarier;
use App\Livewire\Karier\PrediksiKP;
use App\Livewire\Karier\RekomendasiKarier;
use App\Livewire\Karier\TambahRekomKarier;
use App\Livewire\MinatKarir\AdminTesMinatKarir;
use App\Livewire\MinatKarir\TesMinatKarier;
use App\Livewire\Mutasi\Kepala\SimulasiKepala;
use App\Livewire\Mutasi\MutasiPegawai\MutasiPegawai;
use App\Livewire\Mutasi\Pegawai\SimulasiPegawai;
use App\Livewire\Mutasi\Usul\UsulMutasi;
use App\Livewire\Profile\Profile;
use App\Livewire\Ukom\Informasi\DetailInformasiUkom;
use App\Livewire\Ukom\Informasi\EditInfoUkom;
use App\Livewire\Ukom\Informasi\InformasiUkom;
use App\Livewire\Ukom\Informasi\TambahInformasiUkom;
use App\Livewire\Ukom\Jadwal\JadwalUkom;
use App\Livewire\Ukom\Pegawai\DetailInformasiUkom as PegawaiDetailInformasiUkom;
use App\Livewire\Ukom\Pegawai\InfoUkom;
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

//admin
Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/status-abk', StatusABK::class)->name('status-abk');
    Route::get('/detail-abk', DetailDataABK::class)->name('detail-abk');
    Route::get('/tambah-abk', TambahABK::class)->name('tambah-abk');
    Route::get('/update-pegawai', UpdatePegawai::class)->name('update-pegawai');
    Route::get('/update-nomenklatur', UpdateNomenklatur::class)->name('update-nomenklatur');
    Route::get('/simulasi-pegawai', SimulasiPegawai::class)->name('simulasi-pegawai');
    Route::get('/simulasi-kepala', SimulasiKepala::class)->name('simulasi-kepala');
    Route::get('/usul-mutasi', UsulMutasi::class)->name('usul-mutasi');
    Route::get('/angka-kredit', DaftarAngkaKredit::class)->name('angka-kredit');
    Route::get('/buat-pak', BuatPAK::class)->name('buat-pak');
    Route::get('/hasil-pak', HasilPAK::class)->name('hasil-pak');
    Route::get('/ekspor-pak', EksporPak::class)->name('ekspor-pak');
    Route::get('/upload-angka-kredit', UploadAngkaKredit::class)->name('upload-angka-kredit');
    Route::get('/update-kinerja', UpdateKinerja::class)->name('update-kinerja');
    Route::get('/jadwal-ukom', JadwalUkom::class)->name('jadwal-ukom');
    Route::get('/info-ukom', InformasiUkom::class)->name('info-ukom');
    Route::get('/informasi-ukom/{id}', DetailInformasiUkom::class)->name('detail-informasi');
    Route::get('/tambah-info-ukom', TambahInformasiUkom::class)->name('tambah-info-ukom');
    Route::get('/edit-info-ukom/{id}', EditInfoUkom::class)->name('edit-info-ukom');
    Route::get('/info-ukom/{id}', DetailInformasiUkom::class)->name('detail-info');
    Route::get('/karier', AdminRekomKarier::class)->name('karier');
    Route::get('/prediksi-kpkj', PrediksiKP::class)->name('prediksi-kpkj');
    Route::get('/tambah-rekom-karier', TambahRekomKarier::class)->name('tambah-rekom-karier');
    Route::get('/edit-rekom-karier/{id}', EditRekomKarier::class)->name('edit-rekom-karier');
    Route::get('/admin-minat-karier', AdminTesMinatKarir::class)->name('admin-minat-karier');
});

//user
Route::middleware(['auth', 'role:1,2,3'])->group(function () {
    Route::get('/formasi', Formasi::class)->name('formasi');
    Route::get('/rekomendasi-karier', RekomendasiKarier::class)->name('rekomendasi-karier');
    Route::get('/mutasi', MutasiPegawai::class)->name('mutasi');
    Route::get('/angka-kredit-pegawai', AngkaKredit::class)->name('angka-kredit-pegawai');
    Route::get('/informasi-ukom', InfoUkom::class)->name('informasi-ukom');
    Route::get('/info-ukom/{id}', PegawaiDetailInformasiUkom::class)->name('detail-info');
    Route::get('/tes-minat-karier', TesMinatKarier::class)->name('tes-minat-karier');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/dashboard-pegawai', DashboardPegawai::class)->name('dashboard-pegawai');
});


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
