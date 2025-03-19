<?php

namespace App\Livewire\AngkaKredit\Pegawai;

use Livewire\Component;

class AngkaKredit extends Component
{
    public $jenisAngkaKredit = 1; 
    public $periodeMulai, $periodeAkhir, $tahun;

    public function updatedJenisAngkaKredit($value)
    {
        // Reset nilai periode dan angka kredit saat jenis berubah
        $this->periodeMulai = null;
        $this->periodeAkhir = null;
        $this->tahun = null;
    }

    public function render()
    {
        return view('livewire.angka-kredit.pegawai.angka-kredit')->extends('layouts.user');
    }
}
