<?php

use App\Livewire\ABK;
use App\Livewire\Abk\BasedDetailABK;
use App\Livewire\Abk\Detail\DetailDataABK;
use App\Livewire\ABK\DetailABK;
use App\Livewire\Abk\Status\StatusABK;
use App\Livewire\ABK\TabelABK;
use App\Livewire\Abk\Tambah\TambahABK;
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

Route::get('/status-abk', StatusABK::class)->name('status-abk');
Route::get('/detail-abk', DetailDataABK::class)->name('detail-abk');
Route::get('/tambah-abk', TambahABK::class)->name('tambah-abk');
