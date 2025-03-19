<?php

namespace App\Livewire\Mutasi\MutasiPegawai;

use App\Models\Satker;
use Livewire\Component;

class MutasiPegawai extends Component
{
    public $satker;
    public $suggestionsSatker = [];

    public function updatedSatker($value)
    {
        if (empty($value)) {
            $this->suggestionsSatker = [];
            $this->satker = '';
        } else {
            $this->suggestionsSatker = Satker::where('nama', 'like', '%' . $value . '%')
                ->distinct()
                ->limit(5)
                ->pluck('nama')
                ->toArray();
        }
    }

    public function selectSatker($nama)
    {
        $profile = Satker::where('nama', $nama)->first();
        if ($profile) {
            $this->satker = $nama;
            $this->suggestionsSatker = [];
        }
        $this->suggestionsSatker = [];
    }

    public function render()
    {
        return view('livewire.mutasi.mutasi-pegawai.mutasi-pegawai')->extends('layouts.user');
    }
}
