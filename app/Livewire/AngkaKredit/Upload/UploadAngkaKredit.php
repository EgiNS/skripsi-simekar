<?php

namespace App\Livewire\AngkaKredit\Upload;

use Carbon\Carbon;
use App\Models\Profile;
use Livewire\Component;
use App\Models\Golongan;
use App\Models\AngkaKredit;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class UploadAngkaKredit extends Component
{
    public $nip, $nama, $jabatan, $satker, $nilai, $link_pak, $id_pegawai, $editId, $status, $golongan, $tmt_gol;
    public $jenis = 'Tahunan'; 
    public $jenis_angkat_kembali = 'CLTN';
    public $search = '';
    public $periodeMulai, $periodeAkhir, $tahun, $tgl_pengangkatan;
    public $suggestionsNama = [];
    public $showModalEdit = false;

    use WithPagination;

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination saat pencarian berubah
    }

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
                ->distinct()
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
            'nilai' => 'required',
        ]);

        $secondLatest = Profile::where('nip', $this->nip)
            ->orderBy('created_at', 'desc')
            ->skip(1)
            ->take(1)
            ->first()
            ?? Profile::where('nip', $this->nip)
                ->orderBy('created_at', 'desc')
                ->first();    
        
        if ($secondLatest->golongan->nama != $this->golongan) {
            if ($this->golongan == 'III/a' || $this->golongan == 'III/c' || $this->golongan == 'IV/a' || $this->golongan == 'IV/d' || $this->golongan == 'IV/e' ) {
                $ak_total = $this->nilai;
            }
        } else {
            $ak_before = AngkaKredit::where('nip', $this->nip)
                ->orderBy('id', 'desc')
                ->value('total_ak') ?? 0;        

            $ak_total = $ak_before + $this->nilai;
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

        $this->resetPage();

        // Reset form
        $this->reset(['nip', 'nama', 'jabatan', 'satker', 'jenis', 'periodeMulai', 'periodeAkhir', 'tahun', 'nilai', 'link_pak']);

        $this->dispatch('close-modal');

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Angka Kredit Berhasil Ditambahkan!', 'success');
    }

    public function  showPeriode($jenis, $start, $end)
    {
        if ($jenis == 'Konversi Tahunan') {
            $text = Carbon::parse($end)->format('Y');
            return $text;
        } else {
            $tgl_start = Carbon::parse($start)->locale('id');
            $tgl_end = Carbon::parse($end)->locale('id');

            $tgl_start->settings(['formatFunction' => 'translatedFormat']);
            $tgl_end->settings(['formatFunction' => 'translatedFormat']);

            return "{$tgl_start->format('j M Y')} - {$tgl_end->format('j M Y')}";
        }
    }

    public function openModalEdit($id)
    {
        $this->editId = $id;
        $this->showModalEdit = true;
    }

    public function saveEdit()
    {
        if ($this->editId) {
            $data = AngkaKredit::find($this->editId);
            $data->status = $this->status;
            $data->updated_at = Carbon::now();
            $data->save();
        }

        $this->showModalEdit = false;
        $this->dispatch('showFlashMessage', 'Status berhasil diperbarui!', 'success');
    }

    public function render()
    {
        $allApproval = AngkaKredit::with(['profile', 'profile.satker'])
            ->where('status', 1)
            ->when($this->search, function ($query) {
                $query->whereHas('profile', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nip', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        
        // Tambahkan properti periode untuk setiap item
        $allApproval->getCollection()->each(function ($item) {
            $item->periode = $this->showPeriode($item->jenis, $item->periode_start, $item->periode_end);
        });

        return view('livewire.angka-kredit.upload.upload-angka-kredit', compact('allApproval'))->extends('layouts.app');
    }
}
