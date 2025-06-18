<?php

namespace App\Livewire\Mutasi\MutasiPegawai;

use App\Models\Satker;
use App\Models\Profile;
use Livewire\Component;
use App\Models\UsulMutasi;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

class MutasiPegawai extends Component
{
    public $user, $alasan, $id_pegawai, $status, $jenis;
    public $search = '';
    public $suggestionsNama = [];
    public $suggestionsSatker = [];
    public $provinsi, $id_prov;
    public $kabupaten;
    public $provinsiList = [];
    public $kabupatenList = [];
    public $selectedData = [];

    public function mount()
    {
        // Ambil daftar provinsi dari API saat komponen dimuat
        $this->provinsiList = Http::get('https://ibnux.github.io/data-indonesia/provinsi.json')->json();
        $this->user = Profile::where(['nip'=>'198906132012111001', 'active'=>1])->first();

        // array_unshift($this->provinsiList, ['id' => '10', 'nama' => 'BPS RI/POLSTAT STIS/PUSDIKLAT']);
    }


    public function updatedSatker($value)
    {
        if (empty($value)) {
            $this->suggestionsSatker = [];
            $this->satker = '';
        } else {
            $this->suggestionsSatker = Satker::where('nama', 'like', '%' . $value . '%')
                ->distinct()
                ->limit(5)
                ->pluck('nama')
                ->toArray();
        }
    }

    public function selectSatker($nama)
    {
        $profile = Satker::where('nama', $nama)->first();
        if ($profile) {
            $this->satker = $nama;
            $this->suggestionsSatker = [];
        }
        $this->suggestionsSatker = [];
    }

    public function updatedProvinsi($provId)
    {
        $this->id_prov = $provId;
        // Reset pilihan kabupaten saat provinsi berubah
        $this->kabupaten = null;
        $this->kabupatenList = []; // Pastikan daftar kabupaten kosong dulu

        if ($provId == '10') {
            $this->kabupatenList = [
                ["id" => "101", "nama" => "DEPUTI BIDANG STATISTIK DISTRIBUSI"],
                ["id" => "102", "nama" => "DEPUTI BIDANG STATISTIK PRODUKSI"],
                ["id" => "103", "nama" => "DEPUTI BIDANG STATISTIK SOSIAL"],
                ["id" => "104", "nama" => "DEPUTI BIDANG NERACA WILAYAH DAN ANALISIS STATISTIK"],
                ["id" => "105", "nama" => "DEPUTI BIDANG METODOLOGI INFORMASI STATISTIK"],
                ["id" => "106", "nama" => "POLITEKNIK STATISTIKA STIS"],
                ["id" => "107", "nama" => "PUSAT PENDIDIKAN DAN PELATIHAN"],
            ];
        } else {
            $this->kabupatenList = Http::get("https://ibnux.github.io/data-indonesia/kabupaten/{$provId}.json")->json();
        }
    }

    public function createUsul()
    {
        $validatedData = $this->validate([
            'jenis' => 'required',
            'alasan' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
        ]);

        // Simpan ke database
        UsulMutasi::create([
            'id_pegawai' => $this->user->id,
            'nip' => $this->user->nip,
            'prov_tujuan' => collect($this->provinsiList)->firstWhere('id', $this->provinsi)['nama'] ?? '',
            'kab_tujuan' => collect($this->kabupatenList)->firstWhere('id', $this->kabupaten)['nama'] ?? '',
            'jenis' => $this->jenis,
            'alasan' => $this->alasan,
        ]);

        $this->dispatch('refreshTable');

        // Reset form
        $this->reset(['jenis', 'provinsi', 'kabupaten', 'alasan']);

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Pengajuan Mutasi Berhasil Dikirim!', 'success');
    }

    public function render()
    {
        return view('livewire.mutasi.mutasi-pegawai.mutasi-pegawai')->extends('layouts.user');
    }
}
