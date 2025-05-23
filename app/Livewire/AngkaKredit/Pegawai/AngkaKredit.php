<?php

namespace App\Livewire\AngkaKredit\Pegawai;

use Carbon\Carbon;
use App\Models\Profile;
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\AngkaKredit as ModelsAngkaKredit;

class AngkaKredit extends Component
{
    // public $user, $nilai, $link_pak, $id_pegawai, $editId, $status;
    // public $jenis = 'Tahunan'; 
    // public $jenis_angkat_kembali = 'CLTN';
    // public $periodeMulai, $periodeAkhir, $tahun;

    public $user, $nip, $nilai, $link_pak, $id_pegawai, $editId, $status, $golongan, $tmt_gol;
    public $jenis = 'Tahunan'; 
    public $jenis_angkat_kembali = 'CLTN';
    public $search = '';
    public $periodeMulai, $periodeAkhir, $tahun, $tgl_pengangkatan;

    public function mount()
    {
        $this->user = Profile::where(['nip'=>'198906132012111001', 'active'=>1])->first();
    }

    public function updatedJenisAngkaKredit($value)
    {
        // Reset nilai periode dan angka kredit saat jenis berubah
        $this->periodeMulai = null;
        $this->periodeAkhir = null;
        $this->tahun = null;
    }

    public function changeJenis()
    {
        if ($this->jenis == 'Periodik') {
            $last_ak = ModelsAngkaKredit::where('nip', $this->user->nip)
                ->orderBy('id', 'desc')
                ->value('periode_end');

            $this->periodeMulai = $last_ak ? Carbon::parse($last_ak)->startOfMonth()->addMonth()->format('Y-m') : null;
        }
    }

    public function createAngkaKredit()
    {
        $this->validate([
            'nip'  => ['required', Rule::exists('profile', 'nip')],
            'jabatan' => 'required|string|max:255',
            'link_pak' => 'required|string',
            'jenis' => 'required',
            'nilai' => 'required',
        ]);

        $secondLatest = Profile::where('nip', $this->user->nip)
            ->orderBy('created_at', 'desc')
            ->skip(1)
            ->take(1)
            ->first()
            ?? Profile::where('nip', $this->user->nip)
                ->orderBy('created_at', 'desc')
                ->first();    
        
        if ($secondLatest->golongan->nama != $this->user->golongan) {
            if ($this->user->golongan == 'III/a' || $this->user->golongan == 'III/c' || $this->user->golongan == 'IV/a' || $this->user->golongan == 'IV/d' || $this->user->golongan == 'IV/e' ) {
                $ak_total = $this->nilai;
            }
        } else {
            $ak_before = ModelsAngkaKredit::where('nip', $this->nip)
                ->orderBy('id', 'desc')
                ->value('total_ak') ?? 0;        

            $ak_total = $ak_before + $this->nilai;
        }

        if ($this->jenis == 'Tahunan') {
            $this->validate([
                'tahun' => 'required|integer',
            ]);

            ModelsAngkaKredit::create([
                'id_pegawai' => $this->user->id_pegawai,
                'nip' => $this->user->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'status' => 2,
                'nilai' => $this->nilai,
                'total_ak' => $ak_total,
                'periode_start' => $this->tahun . '-01-01',
                'periode_end' => $this->tahun . '-12-31',
            ]);
        } elseif ($this->jenis == 'Periodik'  || $this->jenis == 'Perpindahan Jabatan' || $this->jenis == 'Pengangkatan Kembali') {
            $this->validate([
                'periodeMulai' => 'required',
                'periodeAkhir' => 'required',
            ]);

            ModelsAngkaKredit::create([
                'id_pegawai' => $this->user->id_pegawai,
                'nip' => $this->user->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'status' => 2,
                'nilai' => $this->nilai,
                'total_ak' => $ak_total,
                'periode_start' => Carbon::parse($this->periodeMulai . '-01')->startOfMonth(),
                'periode_end' => Carbon::parse($this->periodeAkhir . '-01')->endOfMonth(),
            ]);
        } elseif ($this->jenis == 'Pengangkatan Pertama') {
            $this->validate([
                'tmt_gol' => 'required',
                'tgl_pengangkatan' => 'required',
            ]);

            ModelsAngkaKredit::create([
                'id_pegawai' => $this->user->id_pegawai,
                'nip' => $this->user->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'status' => 2,
                'nilai' => $this->nilai,
                'total_ak' => $ak_total,
                'periode_start' => $this->tmt_gol,
                'periode_end' => $this->tgl_pengangkatan,
            ]);
        }

        $this->resetPage();

        // Reset form
        $this->reset(['nip', 'nama', 'jabatan', 'satker', 'jenis', 'periodeMulai', 'periodeAkhir', 'tahun', 'nilai', 'link_pak']);

        $this->dispatch('close-modal');

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Angka Kredit Berhasil Ditambahkan!', 'success');
    }

    public function render()
    {
        return view('livewire.angka-kredit.pegawai.angka-kredit')->extends('layouts.user');
    }
}
