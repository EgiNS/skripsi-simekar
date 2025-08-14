<?php

namespace App\Livewire\AngkaKredit\Daftar;

use Carbon\Carbon;
use App\Models\Profile;
use Livewire\Component;
use App\Models\AngkaKredit;
use Illuminate\Validation\Rule;

class DaftarAngkaKredit extends Component
{
    public $nip, $nama, $jabatan, $satker, $nilai, $link_pak, $id_pegawai, $editId, $status, $golongan, $tmt_gol, $total_ak;
    public $jenis = 'Tahunan'; 
    public $jenis_angkat_kembali = 'CLTN';
    public $periodeMulai, $periodeAkhir, $tahun, $tgl_pengangkatan;
    public $suggestionsNama = [];
    public $showModalEdit = false;

    public function updatedNama($value)
    {
        if (empty($value)) {
            $this->suggestionsNama = []; // Kosongkan suggestion jika input NIP kosong
            $this->nip = ''; // Reset nama
            $this->jabatan = ''; // Reset jabatan
            $this->satker = '';
            // $this->tmt_gol = '';
            // $this->periodeMulai = '';
            // $this->periodeAkhir = '';
            // $this->tgl_pengangkatan = '';
        } else {
            // Cari suggestion NIP
            $this->suggestionsNama = Profile::where('active',1)
                ->where('nama', 'like', '%' . $value . '%')
                ->limit(5)
                ->pluck('nama')
                ->toArray();
        }
    }

    public function selectNama($nama)
    {
        $profile = Profile::where(['nama'=>$nama, 'active'=>1])->first();
        if ($profile) {
            $this->nama = $nama;
            $this->nip = $profile->nip;
            $this->id_pegawai = $profile->id;
            $this->jabatan = $profile->jabatan;
            $this->satker = $profile->satker->nama;
            $this->golongan = $profile->golongan->nama;
            $this->tmt_gol = $profile->tmt_gol;
        }
        $this->suggestionsNama = []; // Kosongkan suggestion setelah memilih
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
            $last_ak = AngkaKredit::where('nip', $this->nip)
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
            'nilai' => 'required|numeric',
        ], [
            'link_pak.required' => 'Link PAK wajib diisi.',
            'link_pak.string' => 'Link PAK harus berupa teks.',
            
            'jenis.required' => 'Jenis angka kredit wajib dipilih.',
            
            'nilai.required' => 'Angka kredit wajib diisi.',
            'nilai.numeric' => 'Angka kredit harus berupa angka (gunakan titik untuk angka desimal).',
        ]);

        $secondLatest = Profile::where('nip', $this->nip)
            ->orderBy('created_at', 'desc')
            ->skip(1)
            ->take(1)
            ->first()
            ?? Profile::where('nip', $this->nip)
                ->orderBy('created_at', 'desc')
                ->first();    
        
        if ($this->total_ak != null) {
            $ak_total = $this->total_ak;
        } else {
            if ($secondLatest->golongan->nama != $this->golongan) {
                if ($this->golongan == 'III/a' || $this->golongan == 'III/c' || $this->golongan == 'IV/a' || $this->golongan == 'IV/d' || $this->golongan == 'IV/e' ) {
                    $ak_total = $this->nilai;
                } else {
                    $ak_before = AngkaKredit::where('nip', $this->nip)
                        ->orderBy('id', 'desc')
                        ->value('total_ak') ?? 0;   
    
                    $ak_total = $ak_before + $this->nilai;
                }
            } else {
                $ak_before = AngkaKredit::where('nip', $this->nip)
                    ->orderBy('id', 'desc')
                    ->value('total_ak') ?? 0;        
    
                $ak_total = $ak_before + $this->nilai;
            }
        }

        if ($this->jenis == 'Tahunan') {
            $this->validate([
                'tahun' => 'required|integer',
            ]);

            AngkaKredit::create([
                'id_pegawai' => $this->id_pegawai,
                'nip' => $this->nip,
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

            AngkaKredit::create([
                'id_pegawai' => $this->id_pegawai,
                'nip' => $this->nip,
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

            AngkaKredit::create([
                'id_pegawai' => $this->id_pegawai,
                'nip' => $this->nip,
                'link_pak' => $this->link_pak,
                'jenis' => $this->jenis,
                'status' => 2,
                'nilai' => $this->nilai,
                'total_ak' => $ak_total,
                'periode_start' => $this->tmt_gol,
                'periode_end' => $this->tgl_pengangkatan,
            ]);
        }

        $this->dispatch('refreshTable');

        // Reset form
        $this->reset(['nip', 'nama', 'jabatan', 'satker', 'jenis', 'periodeMulai', 'periodeAkhir', 'tahun', 'nilai', 'link_pak', 'total_ak']);

        $this->dispatch('close-modal');

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Angka Kredit Berhasil Ditambahkan!', 'success');
    }

    public function render()
    {
        return view('livewire.angka-kredit.daftar.daftar-angka-kredit')->extends('layouts.app');
    }
}
