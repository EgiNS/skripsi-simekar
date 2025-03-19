<?php

namespace App\Livewire\AngkaKredit\Upload;

use App\Models\AngkaKredit;
use App\Models\Profile;
use Livewire\Component;
use Illuminate\Validation\Rule;

class UploadAngkaKredit extends Component
{
    public $nip, $nama, $jabatan, $satker, $nilai, $link_pak;
    public $jenis = 1; 
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

    public function createAngkaKredit()
    {
        $this->validate([
            'nip'  => ['required', Rule::exists('profile', 'nip')],
            'jabatan' => 'required|string|max:255',
            'link_pak' => 'required|string',
            'jenis' => 'required|integer',
            'nilai' => 'required|integer',
        ]);

        if ($this->jenis == 3) {
            $this->validate([
                'tahun' => 'required|integer',
            ]);

            AngkaKredit::create([
                'nip' => $this->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'nilai' => $this->nilai,
                'periode_start' => $this->tahun . '-01-01',
                'periode_end' => $this->tahun . '-12-31',
            ]);
        } else {
            $this->validate([
                'periodeMulai' => 'required',
                'periodeAkhir' => 'required',
            ]);

            if($this->jenis == 1 || $this->jenis == 2) {
                $this->validate([
                    'periodeAkhir' => 'required|date|before:2022-12-31',
                ]);
            }

            AngkaKredit::create([
                'nip' => $this->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'nilai' => $this->nilai,
                'periode_start' => $this->periodeMulai,
                'periode_end' => $this->periodeAkhir,
            ]);
        }

        $this->dispatch('refreshTable');

        // Reset form
        $this->reset(['nip', 'nama', 'jabatan', 'satker', 'jenis', 'periodeMulai', 'periodeAkhir', 'tahun', 'nilai', 'link_pak']);

        $this->dispatch('close-modal');

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Angka Kredit Berhasil Ditambahkan!', 'success');
    }

    

    public function render()
    {
        return view('livewire.angka-kredit.upload.upload-angka-kredit')->extends('layouts.app');
    }
}
