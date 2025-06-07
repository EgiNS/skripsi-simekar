<?php

namespace App\Livewire\AngkaKredit\Kinerja;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\NilaiKinerjaImport;
use App\Models\NilaiKinerja;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class UpdateKinerja extends Component
{
    use WithFileUploads;

    public $up_file, $latest;

    public function mount()
    {
        $this->latest = NilaiKinerja::latest('id')->value('created_at');
    }

    public function import()
    {
        $this->validate([
            'up_file' => 'required|mimes:csv,xls,xlsx|max:2048'
        ]);

        if (NilaiKinerja::exists()) {
            NilaiKinerja::truncate(); // Kosongkan seluruh isi tabel
        }

        // membuat nama file unik
        $nama_file = $this->up_file->hashName();

        //temporary file
        $path = $this->up_file->storeAs('csv_uploads',$nama_file);

        // import data
        $import = Excel::import(new NilaiKinerjaImport(), storage_path("app/$path"));

        //remove from server
        Storage::delete($path);

        if($import) {
            $this->latest = NilaiKinerja::latest('id')->value('created_at');
            $this->dispatch('refreshTable');

            $this->dispatch('showFlashMessage', 'Data Kinerja Pegawai Berhasil Diimpor!', 'success');
        } else {
            $this->dispatch('showFlashMessage', 'Data Kinerja Pegawai Gagal Diimpor!', 'error');
        }
    }

    public function render()
    {
        return view('livewire.angka-kredit.kinerja.update-kinerja')->extends('layouts.app');
    }
}
