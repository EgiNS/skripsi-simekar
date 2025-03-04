<?php

namespace App\Livewire\Mutasi\Pegawai;

use Carbon\Carbon;
use App\Models\ABK;
use App\Models\Satker;
use App\Models\Profile;
use Livewire\Component;

class SimulasiPegawai extends Component
{
    public $inputs = []; // Menyimpan daftar input pegawai
    public $allSatker;
    public $suggestionsNama = [];
    public $detailedData = [];
    public $step = 1;
    
    public function mount()
    {
        $this->inputs[] = ['nama' => '', 'satker' => '']; // Mulai dengan satu input
        $this->allSatker = Satker::all();
    }

    public function addInput()
    {
        $this->inputs[] = ['nama' => '', 'satker' => '']; // Tambah input baru
    }

    public function removeInput($index)
    {
        unset($this->inputs[$index]);
        $this->inputs = array_values($this->inputs); // Reset array index
    }

    public function updatedInputs($value, $index)
    {
        if (strpos($index, '.nama') !== false) {
            $key = explode('.', $index)[0]; // Ambil index angka
            $this->suggestionsNama[$key] = Profile::where('nama', 'like', '%' . $value . '%')
                ->distinct()
                ->limit(5)
                ->pluck('nama')
                ->toArray();
        }
    }

    public function selectNama($index, $nama)
    {
        $this->inputs[$index]['nama'] = $nama;
        $this->suggestionsNama[$index] = [];
    }

    public function nextPage()
    {
        $this->detailedData = [];

        foreach ($this->inputs as $input) {
            $profile = Profile::where('nama', $input['nama'])->first();

            if ($profile) {
                $jabatan = $profile->jabatan;
                $satkerTujuan = $input['satker'];

                // Ambil semua satker yang memiliki formasi lebih besar dari eksisting
                $satkerList = ABK::where('jabatan', $jabatan)
                    ->get()
                    ->filter(function ($abk) {
                        $eksisting = $this->getEksisting($abk->jabatan, $abk->id_satker);
                        return $abk->formasi > $eksisting;
                    })
                    ->map(function ($abk) {
                        return [
                            'id' => $abk->id_satker,
                            'nama' => Satker::find($abk->id_satker)->nama ?? 'Tidak Ditemukan',
                            'formasi' => $abk->formasi,
                            'eksisting' => $this->getEksisting($abk->jabatan, $abk->id_satker),
                        ];
                    })
                    ->values();

                // Simpan data lengkap
                $this->detailedData[] = [
                    'nama' => $profile->nama,
                    'nip' => $profile->nip,
                    'jabatan' => $profile->jabatan,
                    'satker_asal' => $profile->satker->nama,
                    'tmt_jab' => $this->hitungMasaKerja($profile->tmt_jab),
                    'tmt_cpns' => $this->hitungMasaKerja($profile->tmt_cpns),
                    'satker_tujuan' => Satker::find($satkerTujuan)->nama ?? 'Tidak Ditemukan',
                    'formasi' => $satkerList->first()['formasi'] ?? 0,
                    'eksisting' => $satkerList->first()['eksisting'] ?? 0,
                    'satker_eligible' => $satkerList, // Simpan semua satker yang eligible
                ];
            }
        }
    
        // Pindah ke halaman detail pegawai
        $this->step = 2;
    }

    public function prevPage()
    {
        $this->step = 1; // Kembali ke halaman pertama
    }

    public function getEksisting($jabatan, $satker)
    {
        return Profile::where(['jabatan'=>$jabatan, 'id_satker'=>$satker])
            ->count();
    }

    private function hitungMasaKerja($tmt)
    {
        if (!$tmt) return '-';

        $tmt = Carbon::parse($tmt);
        $sekarang = Carbon::now();
        $selisih = $tmt->diff($sekarang);

        return "{$selisih->y} tahun {$selisih->m} bulan {$selisih->d} hari";
    }

    public function render()
    {
        return view('livewire.mutasi.pegawai.simulasi-pegawai')->extends('layouts.app');
    }
}
