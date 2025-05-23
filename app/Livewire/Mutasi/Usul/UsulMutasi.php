<?php

namespace App\Livewire\Mutasi\Usul;

use Carbon\Carbon;
use App\Models\Satker;
use App\Models\Profile;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Models\UsulMutasi as ModelsUsulMutasi;

class UsulMutasi extends Component
{
    public $editId;
    public $nip, $nama, $jabatan, $satker, $satker_asal, $alasan, $id_pegawai, $status;
    public $jenis = 'Atas Permintaan Sendiri';
    public $search = '';
    public $suggestionsNama = [];
    public $suggestionsSatker = [];
    public $provinsi;
    public $kabupaten;
    public $provinsiList = [];
    public $kabupatenList = [];
    public $selectedData = [];
    public $showModalEdit = false;

    use WithPagination;

    public function mount()
    {
        // Ambil daftar provinsi dari API saat komponen dimuat
        $this->provinsiList = Http::get('https://ibnux.github.io/data-indonesia/provinsi.json')->json();

        array_unshift($this->provinsiList, ['id' => '10', 'nama' => 'BPS RI/POLSTAT STIS/PUSDIKLAT']);
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination saat pencarian berubah
    }

    public function updatedNama($value)
    {
        if (empty($value)) {
            $this->suggestionsNama = []; // Kosongkan suggestion jika input NIP kosong
            $this->nama = ''; // Reset nama
            $this->jabatan = ''; // Reset jabatan
        } else {
            // Cari suggestion NIP
            $this->suggestionsNama = Profile::where('active', 1)
                ->where('nama', 'like', '%' . $value . '%')
                ->distinct()
                ->limit(5)
                ->pluck('nama')
                ->toArray();
        }
    }

    public function selectNama($nama)
    {
        $profile = Profile::where(['nama' => $nama, 'active' => 1])->first();
        if ($profile) {
            $this->nama = $nama;
            $this->nip = $profile->nip;
            $this->id_pegawai = $profile->id;
            $this->jabatan = $profile->jabatan;
            $this->satker_asal = $profile->satker->nama;
        }
        $this->suggestionsNama = []; // Kosongkan suggestion setelah memilih
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

    public function createUsul()
    {
        $validatedData = $this->validate([
            'id_pegawai'  => ['required', Rule::exists('profile', 'id')],
            'jabatan' => 'required|string|max:255',
            'jenis' => 'required',
            'alasan' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
        ]);

        // Simpan ke database
        ModelsUsulMutasi::create([
            'id_pegawai' => $this->id_pegawai,
            'nip' => $this->nip,
            'prov_tujuan' => collect($this->provinsiList)->firstWhere('id', $this->provinsi)['nama'] ?? '',
            'kab_tujuan' => collect($this->kabupatenList)->firstWhere('id', $this->kabupaten)['nama'] ?? '',
            'jenis' => $this->jenis,
            'alasan' => $this->alasan,
        ]);

        $this->resetPage();

        // Reset form
        $this->reset(['nip', 'nama', 'jabatan', 'satker', 'jenis', 'provinsi', 'kabupaten', 'alasan']);

        $this->dispatch('close-modal');

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Usul Mutasi Berhasil Ditambahkan!', 'success');
    }

    public function updatedProvinsi($provId)
    {
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

    public function openModalEdit($id)
    {
        $this->editId = $id;
        $this->showModalEdit = true;
    }

    public function saveEdit()
    {
        if ($this->editId) {
            $data = ModelsUsulMutasi::find($this->editId);
            $data->status = $this->status;
            $data->updated_at = Carbon::now();
            $data->save();
        }

        $this->resetPage();

        $this->showModalEdit = false;
        $this->dispatch('showFlashMessage', 'Status berhasil diperbarui!', 'success');
    }

    public function saveSelectedPegawai($selecteds)
    {
        $this->selectedData = [];

        foreach ($selecteds as $selected) {
            $pegawai = ModelsUsulMutasi::where('id', (int) $selected)->first();
            
            if ($pegawai) {
                $this->selectedData[] = [
                    'nama' => Profile::where('id', $pegawai->id_pegawai)->value('nama'),
                    'satker' => Satker::where('wilayah', $pegawai->kab_tujuan)->value('id'),
                ];
            }
        }
    
        session()->put('selectedData', $this->selectedData);

        $this->dispatch('navigateTo', '/simulasi-pegawai');
    }

    public function render()
    {
        // return view('livewire.mutasi.usul.usul-mutasi', [
        //     'allUsul' => ModelsUsulMutasi::paginate(10), // Panggil paginate langsung di render()
        // ])->extends('layouts.app');

        $allUsul = ModelsUsulMutasi::with(['profile', 'profile.satker'])
                ->when($this->search, function ($query) {
                    $query->whereHas('profile', function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('nip', 'like', '%' . $this->search . '%')
                        ->orWhere('prov_tujuan', 'like', '%' . $this->search . '%')
                        ->orWhere('kab_tujuan', 'like', '%' . $this->search . '%')
                        ->orWhere('jenis', 'like', '%' . $this->search . '%')
                        ->orWhere('jabatan', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(6);

        return view('livewire.mutasi.usul.usul-mutasi', compact('allUsul'))->extends('layouts.app');
    }
}
