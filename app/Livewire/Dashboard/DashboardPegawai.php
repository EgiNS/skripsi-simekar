<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Ukom;
use App\Models\Profile;
use Livewire\Component;
use App\Models\AngkaKredit;

class DashboardPegawai extends Component
{
    public $user, $ak, $kp, $kj, $pensiun, $ukom;

    public array $nilaiJenjang = [
        'terampil' => 5,
        'mahir' => 12.5,
        'ahli pertama' => 12.5,
        'penyelia' => 25,
        'ahli muda' => 25,
        'ahli madya' => 37.5,
        'ahli utama' => 50,
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

    public array $ak_jenjang = [
        'II/c' => 40,
        'II/d' => 40,
        'III/a' => 100,
        'III/b' => 100,
        'III/c' => 200,
        'III/d' => 200,
        'IV/a' => 450,
        'IV/b' => 450,
        'IV/c' => 450,
    ];

    public function mount()
    {
        $this->user = Profile::where(['nip'=>'198906132012111001', 'active'=>1])->first();
        $this->ak = AngkaKredit::where('nip', $this->user->nip)->orderBy('id', 'desc')->first();
        $this->kp = $this->naikPangkat($this->ak->total_ak, $this->ak->periode_end);
        $this->kj = $this->naikJenjang($this->ak->total_ak, $this->ak->periode_end);
        $this->pensiun = $this->getPensiun($this->user->jabatan)->translatedFormat('d F Y');
        $this->ukom =  Ukom::latest()->take(5)->get()->sortBy('id')->values();
    }

    public function naikPangkat($ak, $periode_end)
    {
        $ak_kp = $this->user->golongan->ak_minimal;

        $jenjang = $this->gol_jenjang[$this->user->golongan->nama][0];
        $ak_tahunan = $this->nilaiJenjang[$jenjang];

        $pred_kp = ceil(($ak_kp - $ak) / $ak_tahunan * 12);

        $perkiraan_kp = Carbon::parse($periode_end)->startOfMonth()->addMonths($pred_kp);

        return $perkiraan_kp->format('F Y');
    }

    public function naikJenjang($ak, $periode_end)
    {
        $ak_kj = isset($this->ak_jenjang[$this->user->golongan->nama]) ? $this->ak_jenjang[$this->user->golongan->nama] : '-';

        $jenjang = $this->gol_jenjang[$this->user->golongan->nama][0];
        $ak_tahunan = $this->nilaiJenjang[$jenjang];

        $pred_kj = ceil(($ak_kj - $ak) / $ak_tahunan * 12);

        $perkiraan_kj = Carbon::parse($periode_end)->startOfMonth()->addMonths($pred_kj);

        return $perkiraan_kj->format('F Y');
    }

    public function getPensiun($jab)
    {
        $jenjangList = [
            'terampil',
            'mahir',
            'penyelia',
            'ahli pertama',
            'ahli muda',
            'ahli madya',
            'ahli utama',
        ];

        // Ubah ke lowercase biar konsisten
        $jabatanLower = strtolower($jab);

        // Cari jenjang yang ada di dalam string jabatan
        $jenjangSaatIni = null;
        foreach ($jenjangList as $jenjang) {
            if (str_contains($jabatanLower, $jenjang)) {
                $jenjangSaatIni = $jenjang;
                break;
            }
        }

        if ($jenjangSaatIni == 'ahli utama') {
            return Carbon::parse($this->user->tgl_lahir)->copy()->addYears(65);
        } elseif ($jenjangSaatIni == 'ahli madya') {
            return Carbon::parse($this->user->tgl_lahir)->copy()->addYears(60);
        } else {
            return Carbon::parse($this->user->tgl_lahir)->copy()->addYears(58);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-pegawai')->extends('layouts.user');
    }
}
