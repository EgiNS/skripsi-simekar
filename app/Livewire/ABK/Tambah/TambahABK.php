<?php

namespace App\Livewire\Abk\Tambah;

use App\Models\Jabatan;
use App\Models\Satker;
use Livewire\Component;

class TambahABK extends Component
{
    public $jabatan = '';
    public $suggestions = [];
    public $satker, $allSatker, $formasi;

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
    
    public function render()
    {
        $this->allSatker = Satker::all();
        return view('livewire.abk.tambah.tambah-a-b-k')->extends('layouts.app');
    }
}
