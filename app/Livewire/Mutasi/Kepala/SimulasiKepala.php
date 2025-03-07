<?php

namespace App\Livewire\Mutasi\Kepala;

use Carbon\Carbon;
use App\Models\Profile;
use Livewire\Component;

class SimulasiKepala extends Component
{
    public $step = 1;
    public $selectedData = []; // Tambahkan ini untuk menyimpan data yang dipilih
    public $allSatker = [];
    public $kandidat = '';
    public $suggestions = [];

    protected $listeners = ['ubahStep' => 'ubahStep']; // Dengarkan event 'ubahStep'

    // Method untuk mengubah step
    public function ubahStep($step, $selectedData, $allSatker)
    {
        $this->step = $step;
        $this->selectedData = $selectedData;
        $this->allSatker = $allSatker;
    }

    public function prevPage()
    {
        $this->step = 1; // Kembali ke halaman pertama
    }

    public function updatedKandidat()
    {
        $this->suggestions = Profile::where('nama', 'like', '%' . $this->kandidat . '%')
            ->whereBetween('id_golongan', [8, 13])
            ->limit(5)
            ->pluck('nama')
            ->toArray();
    }

    public function selectKandidat($kandidat)
    {
        // $this->selectedData[] = Profile::where('nama', $kandidat)->pluck('nip');
        $profile = Profile::where('nama', $kandidat)->first();
        $this->selectedData[] = [
            'nama' => $profile->nama,
            'nip' => $profile->nip,
            'jabatan' => $profile->jabatan,
            'satker_asal' => $profile->satker->nama,
            'zona' => null,
            'tmt_jab' => $this->hitungMasaKerja($profile->tmt_jab, 'jab'),
        ];
        $this->suggestions = [];
    }

    private function hitungMasaKerja($tmt, $jenis)
    {
        if (!$tmt) return '-';

        $tmt = Carbon::parse($tmt);
        $sekarang = Carbon::now();
        $selisih = $tmt->diff($sekarang);
        

        if ($jenis == "jab") {
            return "{$selisih->y} tahun {$selisih->m} bulan {$selisih->d} hari";
        } else {
            return "{$selisih->y} tahun {$selisih->m} bulan";
        }
    }

    public function hapusData($nip)
    {
        // Cari index data berdasarkan NIP
        $index = array_search($nip, array_column($this->selectedData, 'nip'));

        // Jika data ditemukan, hapus dari array
        if ($index !== false) {
            unset($this->selectedData[$index]);
            $this->selectedData = array_values($this->selectedData); // Reset array keys
        }
    }

    public function render()
    {
        return view('livewire.mutasi.kepala.simulasi-kepala')->extends('layouts.app');
    }
}
