<?php

namespace App\Livewire\Mutasi\Usul;

use App\Models\Profile;
use App\Models\Satker;
use Livewire\Component;

class UsulMutasi extends Component
{
    public $nip, $nama, $jabatan, $satker;
    public $suggestionsNip = [];
    public $suggestionsSatker = [];

    public function updatedNip()
    {
        $this->suggestionsNip = Profile::where('nip', 'like', '%' . $this->jabatan . '%')
            ->distinct()
            ->limit(5)
            ->pluck('nip')
            ->toArray();
    }

    public function selectNip($nip)
    {
        $profile = Profile::where('nip', $nip)->first();
        $this->nip = $nip;
        $this->nama = $profile->nama;
        $this->jabatan = $profile->jabatan;
        $this->suggestionsNip = [];
    }

    public function updatedSatker()
    {
        $this->suggestionsSatker = Satker::where('nama', 'like', '%' . $this->jabatan . '%')
            ->distinct()
            ->limit(5)
            ->pluck('nama')
            ->toArray();
    }

    public function selectSatker($nama)
    {
        $profile = Satker::where('nama', $nama)->first();
        if ($profile) {
            $this->satker = $nama;
            $this->suggestionsSatker = [];
        }
    }


    public function render()
    {
        return view('livewire.mutasi.usul.usul-mutasi')->extends('layouts.app');
    }
}
