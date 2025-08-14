<?php

namespace App\Livewire\Abk\Tambah;

use App\Models\ABK;
use App\Models\Satker;
use App\Models\Jabatan;
use Livewire\Component;
use Illuminate\Validation\Rule;

class TambahABK extends Component
{
    public $jabatan = '';
    public $suggestions = [];
    public $allSatker, $formasi;
    public $satker = 1100;

    public function updatedJabatan()
    {
        $this->suggestions = Jabatan::where('konversi', 'like', '%' . $this->jabatan . '%')
            ->distinct()
            ->limit(5)
            ->pluck('konversi')
            ->toArray();
    }

    public function selectJabatan($jabatan)
    {
        $this->jabatan = $jabatan;
        $this->suggestions = [];
    }

    public function createABK()
    {
        // Validasi input
        $validatedData = $this->validate([
            'satker'  => 'required',
            'jabatan' => 'required',
            'formasi' => 'required|integer',
        ]);

        // Simpan ke database
        ABK::create([
            'id_satker' => $this->satker,
            'jabatan'   => $this->jabatan,
            'formasi'   => $this->formasi,
        ]);

        Jabatan::create([
            'nama_simpeg' => $this->jabatan,
            'konversi' => $this->jabatan,
            'nama_umum' => $this->jabatan
        ]);

        // Reset form
        $this->reset(['satker', 'jabatan', 'formasi']);

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Formasi Berhasil Ditambahkan!', 'success');
    }
    
    public function render()
    {
        $this->allSatker = Satker::all();
        return view('livewire.abk.tambah.tambah-a-b-k')->extends('layouts.app');
    }
}
