<?php

namespace App\Livewire\AngkaKredit\Upload;

use App\Models\Profile;
use Livewire\Component;

class UploadAngkaKredit extends Component
{
    public $nip, $nama, $jabatan, $satker;
    public $jenisAngkaKredit = 1; 
    public $periodeMulai, $periodeAkhir, $tahun;
    public $suggestionsNip = [];

    public function updatedNip($value)
    {
        if (empty($value)) {
            $this->suggestionsNip = []; // Kosongkan suggestion jika input NIP kosong
            $this->nama = ''; // Reset nama
            $this->jabatan = ''; // Reset jabatan
            $this->satker = '';
        } else {
            // Cari suggestion NIP
            $this->suggestionsNip = Profile::where('nip', 'like', '%' . $value . '%')
                ->distinct()
                ->limit(5)
                ->pluck('nip')
                ->toArray();
        }
    }

    public function selectNip($nip)
    {
        $profile = Profile::where('nip', $nip)->first();
        if ($profile) {
            $this->nip = $nip;
            $this->nama = $profile->nama;
            $this->jabatan = $profile->jabatan;
            $this->satker = $profile->satker->nama;
        }
        $this->suggestionsNip = []; // Kosongkan suggestion setelah memilih
    }

    public function updatedJenisAngkaKredit($value)
    {
        // Reset nilai periode dan angka kredit saat jenis berubah
        $this->periodeMulai = null;
        $this->periodeAkhir = null;
        $this->tahun = null;
    }

    public function render()
    {
        return view('livewire.angka-kredit.upload.upload-angka-kredit')->extends('layouts.app');
    }
}
