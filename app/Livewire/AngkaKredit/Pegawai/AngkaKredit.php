<?php

namespace App\Livewire\AngkaKredit\Pegawai;

use Carbon\Carbon;
use App\Models\Profile;
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\AngkaKredit as ModelsAngkaKredit;
use Illuminate\Support\Facades\Auth;

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
        $this->user = Profile::where(['username'=>Auth::user()->username, 'active'=>1])->first();
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
            'link_pak' => 'required|string',
            'jenis' => 'required',
            'nilai' => 'required|numeric',
        ], [
            'link_pak.required' => 'Link PAK wajib diisi.',
            'link_pak.string' => 'Link PAK harus berupa teks.',
            
            'jenis.required' => 'Jenis angka kredit wajib dipilih.',
            
            'nilai.required' => 'Angka kredit wajib diisi.',
            'nilai.numeric' => 'Angka kredit harus berupa angka (gunakan titik untuk angka desimal).',
        ]);

        $secondLatest = Profile::where('nip', $this->user->nip)
            ->orderBy('created_at', 'desc')
            ->skip(1)
            ->take(1)
            ->first()
            ?? Profile::where('nip', $this->user->nip)
                ->orderBy('created_at', 'desc')
                ->first();    
        
        if ($secondLatest->golongan->nama != $this->user->golongan->nama) {
            if ($this->user->golongan->nama == 'III/a' || $this->user->golongan->nama == 'III/c' || $this->user->golongan->nama == 'IV/a' || $this->user->golongan->nama == 'IV/d' || $this->user->golongan->nama == 'IV/e' ) {
                $ak_total = $this->nilai;
            } else {
                $ak_before = ModelsAngkaKredit::where('nip', $this->user->nip)
                    ->orderBy('id', 'desc')
                    ->value('total_ak') ?? 0;   

                $ak_total = $ak_before + $this->nilai;
            }
        } else {
            $ak_before = ModelsAngkaKredit::where('nip', $this->user->nip)
                ->orderBy('id', 'desc')
                ->value('total_ak') ?? 0;   

            $ak_total = $ak_before + $this->nilai;
        }

        if ($this->jenis == 'Tahunan') {
            $this->validate([
                'tahun' => 'required|integer',
            ]);

            ModelsAngkaKredit::create([
                'id_pegawai' => $this->user->id,
                'nip' => $this->user->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'status' => 1,
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
                'id_pegawai' => $this->user->id,
                'nip' => $this->user->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'status' => 1,
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
                'id_pegawai' => $this->user->id,
                'nip' => $this->user->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'status' => 1,
                'nilai' => $this->nilai,
                'total_ak' => $ak_total,
                'periode_start' => $this->tmt_gol,
                'periode_end' => $this->tgl_pengangkatan,
            ]);
        }

        $this->dispatch('refreshTable');

        $this->dispatch('close-modal');
        
        // Reset form
        $this->reset(['jenis', 'periodeMulai', 'periodeAkhir', 'tahun', 'nilai', 'link_pak']);

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Angka Kredit Berhasil Ditambahkan!', 'success');
    }

    public function render()
    {
        return view('livewire.angka-kredit.pegawai.angka-kredit')->extends('layouts.user');
    }
}
