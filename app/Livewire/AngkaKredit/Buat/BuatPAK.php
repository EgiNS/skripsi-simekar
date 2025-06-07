<?php

namespace App\Livewire\AngkaKredit\Buat;

use DateTime;
use Carbon\Carbon;
use App\Models\Satker;
use App\Models\Profile;
use Livewire\Component;
use App\Models\Golongan;
use App\Models\AngkaKredit;
use App\Models\NilaiKinerja;

class BuatPAK extends Component
{
    public $jenis = 'Tahunan';
    public $akhir_periode, $tgl_pengangkatan, $tgl_pelantikan;
    public $jenis_angkat_kembali = 'CLTN';
    public $tgl_mulai_tb, $tgl_akhir_tb;
    public $is_cumlaude = false;
    public $showModal = false;
    public $selectedProfiles = [];
    public $jenjangList = [
        'terampil',
        'mahir',
        'penyelia',
        'ahli pertama',
        'ahli muda',
        'ahli madya',
        'ahli utama',
    ];
    public array $nilaiJenjang = [
        'terampil' => 5,
        'mahir' => 12.5,
        'ahli pertama' => 12.5,
        'penyelia' => 25,
        'ahli muda' => 25,
        'ahli madya' => 37.5,
        'ahli utama' => 50,
    ];
    public array $persenPredikat = [
        'sangat kurang' => 0.25,
        'kurang' => 0.5,
        'cukup' => 0.75,
        'baik' => 1,
        'sangat baik' => 1.5,
    ];
    public array $gol_jenjang = [
        'II/c' => ['terampil'],
        'II/d' => ['terampil'],
        'III/a' => ['ahli pertama', 'mahir'],
        'III/b' => ['ahli pertama', 'mahir'],
        'III/c' => ['ahli muda', 'penyelia'],
        'III/d' => ['ahli muda', 'penyelia'],
        'IV/a' => ['ahli madya'],
        'IV/b' => ['ahli madya'],
        'IV/c' => ['ahli madya'],
        'IV/d' => ['ahli utama'],
        'IV/e' => ['ahli utama'],
    ]; 

    protected $listeners = ['openModalBuatPAK' => 'handleOpenModal'];
    
    public function mount()
    {
        $this->akhir_periode = now()->subMonth()->format('Y-m');
    }

    public function handleOpenModal($profiles)
    {
        $this->selectedProfiles = $profiles;
        $this->showModal = true;
    }

    public function createPAK()
    {
        $this->validate([
            'jenis' => 'required',
        ]);

        if ($this->jenis == 'Tahunan') {
            foreach ($this->selectedProfiles as &$profile) {
                $profile['jenjang'] = $this->getJenjangFromJabatan($profile['jabatan'] ?? '');
                $profile['predikat'] = NilaiKinerja::where('nip', $profile['nip'])->value('predikat') ?? 'Baik';
                $profile['satker'] = Satker::where('id', $profile['id_satker'])->value('nama');
                $profile['periode'] = date('Y') - 1;

                $ak_before = AngkaKredit::where('nip', $profile['nip'])
                    ->orderBy('id', 'desc')
                    ->first();
                
                if ($ak_before) {
                    $profile['ak_awal'] = $ak_before->total_ak;
                    if ($ak_before != 'Konversi Tahunan') {
                        $last_periode =  Carbon::parse($ak_before->periode_end)->startOfMonth()->addMonth();
                        $akhir_tahun = $last_periode->copy()->endOfYear();
                        
                        $profile['selisih_bulan'] = $last_periode->diffInMonths($akhir_tahun) + 1;
                        $profile['angka_kredit'] = (($profile['selisih_bulan']/12) * $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']]);
                    } else {
                        $profile['angka_kredit'] = $this->nilaiJenjang[$profile['jenjang']] * $this->persenPredikat[strtolower($profile['predikat'])];
                    }
                } else {
                    $profile['ak_awal'] = 0;
                    $profile['angka_kredit'] = $this->nilaiJenjang[$profile['jenjang']] * $this->persenPredikat[strtolower($profile['predikat'])];
                }
            }
        } elseif ($this->jenis == 'Periodik') {
            foreach ($this->selectedProfiles as &$profile) {
                $profile['jenjang'] = $this->getJenjangFromJabatan($profile['jabatan'] ?? '');
                $profile['predikat'] = NilaiKinerja::where('nip', $profile['nip'])->value('predikat') ?? 'Baik';
                $profile['satker'] = Satker::where('id', $profile['id_satker'])->value('nama');

                $ak_before = AngkaKredit::where('nip', $profile['nip'])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($ak_before) {
                    // Hitung awal dan akhir periode
                    $profile['mulai'] = Carbon::parse($ak_before->periode_end)->startOfMonth()->addMonth();
                    $profile['akhir'] = Carbon::parse($this->akhir_periode . '-01')->endOfMonth();

                    // Hitung selisih bulan
                    $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                    $profile['ak_awal'] = $ak_before->total_ak;
                    $profile['angka_kredit'] = (($selisih_bulan/12) *  $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']]);
                } else {
                    $profile['mulai'] = Carbon::now()->startOfYear();
                    $profile['akhir'] = Carbon::parse($this->akhir_periode . '-01')->endOfMonth();

                    // Hitung selisih bulan
                    $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                    $profile['ak_awal'] = 0;
                    $profile['angka_kredit'] = ($selisih_bulan/12) * $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']];
                }
            }
        } elseif ($this->jenis == 'Pengangkatan Pertama') {
            foreach ($this->selectedProfiles as &$profile) {
                $profile['predikat'] = NilaiKinerja::where('nip', $profile['nip'])->value('predikat') ?? 'Baik';
                $profile['satker'] = Satker::where('id', $profile['id_satker'])->value('nama');
                $profile['golongan'] = Golongan::where('id', $profile['id_golongan'])->value('nama'); 
                $profile['jenjang_tujuan'] = $this->gol_jenjang[$profile['golongan']][0];
                
                $profile['mulai'] = Carbon::parse($profile['tmt_gol']);
                $profile['akhir'] =  Carbon::parse($this->tgl_pelantikan)->subMonth()->endOfMonth();

                $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                $ak_dasar = Golongan::where('nama', $profile['golongan'])->value('ak_dasar'); 

                $profile['ak_dasar'] = $ak_dasar;
                $profile['ak_awal'] = 0;
                $profile['angka_kredit'] = ($selisih_bulan/12) * $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang_tujuan']] + $ak_dasar;
            }
        } elseif ($this->jenis == 'Perpindahan Jabatan') {
            foreach ($this->selectedProfiles as &$profile) {
                $profile['jenjang'] = $this->getJenjangFromJabatan($profile['jabatan'] ?? '');
                $profile['predikat'] = NilaiKinerja::where('nip', $profile['nip'])->value('predikat') ?? 'Baik';
                $profile['satker'] = Satker::where('id', $profile['id_satker'])->value('nama');

                $ak_before = AngkaKredit::where('nip', $profile['nip'])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($ak_before) {
                    $profile['mulai'] = Carbon::parse($ak_before->periode_end)->startOfMonth()->addMonth();
                    $profile['akhir'] = Carbon::parse($this->akhir_periode . '-01')->endOfMonth();

                    // Hitung selisih bulan
                    $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                    $profile['ak_awal'] = $ak_before->total_ak;
                    $profile['angka_kredit'] = (($selisih_bulan/12) *  $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']]);
                } else {
                    $profile['mulai'] = Carbon::now()->startOfYear();
                    $profile['akhir'] = Carbon::parse($this->akhir_periode . '-01')->endOfMonth();

                    // Hitung selisih bulan
                    $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                    $profile['ak_awal'] = 0;
                    $profile['angka_kredit'] = ($selisih_bulan/12) * $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']];
                }
            }
        } elseif(($this->jenis_angkat_kembali == 'CLTN')) {
            foreach ($this->selectedProfiles as &$profile) {
                $profile['jenjang'] = $this->getJenjangFromJabatan($profile['jabatan'] ?? '');
                $profile['predikat'] = NilaiKinerja::where('nip', $profile['nip'])->value('predikat') ?? 'Baik';
                $profile['satker'] = Satker::where('id', $profile['id_satker'])->value('nama');

                $ak_before = AngkaKredit::where('nip', $profile['nip'])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($ak_before) {
                    $profile['mulai'] = Carbon::parse($this->tgl_pengangkatan)->addMonthNoOverflow()->startOfMonth();
                    $profile['akhir'] = Carbon::parse($this->akhir_periode . '-01')->endOfMonth();

                    // Hitung selisih bulan
                    $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                    $profile['ak_awal'] = $ak_before->total_ak;
                    $profile['angka_kredit'] = (($selisih_bulan/12) *  $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']]);
                } else {
                    $profile['mulai'] = Carbon::parse($this->tgl_pengangkatan)->addMonthNoOverflow()->startOfMonth();
                    $profile['akhir'] = Carbon::parse($this->akhir_periode . '-01')->endOfMonth();

                    // Hitung selisih bulan
                    $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                    $profile['ak_awal'] = 0;
                    $profile['angka_kredit'] = ($selisih_bulan/12) * $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']];
                }
            }
        } elseif ($this->jenis_angkat_kembali == 'Tugas Belajar') {
            foreach ($this->selectedProfiles as &$profile) {
                $profile['jenjang'] = $this->getJenjangFromJabatan($profile['jabatan'] ?? '');
                // dd($this->is_cumlaude);
                if ($this->is_cumlaude) {
                    $profile['predikat'] = 'Sangat Baik';
                    $persentase = 1.5;
                } else {
                    $profile['predikat'] = 'Baik';
                    $persentase = 1;
                }
                $profile['golongan'] = Golongan::where('id', $profile['id_golongan'])->value('nama'); 
                $profile['satker'] = Satker::where('id', $profile['id_satker'])->value('nama');

                $profile['mulai'] = Carbon::parse($this->tgl_mulai_tb);
                $profile['akhir'] =  Carbon::parse($this->tgl_akhir_tb);

                $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                $profile['lama_tb'] = $selisih_bulan;

                $ak_before = AngkaKredit::where('nip', $profile['nip'])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($ak_before) {
                    $ak_pend = 0.25 * Golongan::where('nama', $profile['golongan'])->value('ak_minimal');

                    $profile['ak_pend'] = $ak_pend;
                    $profile['ak_awal'] = $ak_before->total_ak;
                    $profile['angka_kredit'] = $ak_pend + ($profile['lama_tb'])/12 * $persentase * $this->nilaiJenjang[$profile['jenjang']];
                } else {
                    $ak_pend = 0.25 * Golongan::where('nama', $profile['golongan'])->value('ak_minimal');

                    $profile['ak_pend'] = $ak_pend;
                    $profile['ak_awal'] = 0;
                    $profile['angka_kredit'] = $ak_pend + (($profile['lama_tb'])/12 * $persentase * $this->nilaiJenjang[$profile['jenjang']]);
                }
            }
        } elseif ($this->jenis_angkat_kembali == 'Struktural ke JFT') {
            foreach ($this->selectedProfiles as &$profile) {  
                $profile['predikat'] = NilaiKinerja::where('nip', $profile['nip'])->value('predikat') ?? 'Baik';
                $profile['satker'] = Satker::where('id', $profile['id_satker'])->value('nama');
                $profile['golongan'] = Golongan::where('id', $profile['id_golongan'])->value('nama'); 
                $profile['jenjang'] = $this->gol_jenjang[$profile['golongan']][0];
                
                $profile['mulai'] = Carbon::parse($profile['tmt_jab']);
                $profile['akhir'] =  Carbon::parse($this->tgl_pengangkatan)->subMonth()->endOfMonth();

                $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;

                $ak_before = AngkaKredit::where('nip', $profile['nip'])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($ak_before) {
                    $profile['ak_awal'] = $ak_before->total_ak;
                    $profile['angka_kredit'] = ($selisih_bulan/12) * $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']];
                } else {
                    $profile['ak_awal'] = 0;
                    $profile['angka_kredit'] = ($selisih_bulan/12) * $this->persenPredikat[strtolower($profile['predikat'])] * $this->nilaiJenjang[$profile['jenjang']];
                }
            }
        }

        session()->put('selectedProfiles', $this->selectedProfiles);
        session()->put('jenis', $this->jenis);
        if ($this->jenis == 'Pengangkatan Kembali') {
            session()->put('jenis_angkat_kembali', $this->jenis_angkat_kembali);
        }

        $this->dispatch('navigateTo', '/hasil-pak');
    }

    public function getJenjangFromJabatan($jabatan)
    {
        $jabatan = strtolower($jabatan);

        foreach ($this->jenjangList as $jenjang) {
            if (str_contains($jabatan, strtolower($jenjang))) {
                return $jenjang;
            }
        }

        return null; // kalau tidak ketemu
    }

    public function render()
    {
        return view('livewire.angka-kredit.buat.buat-p-a-k')->extends('layouts.app');
    }
}
