<?php

namespace App\Livewire\Mutasi\Usul;

use App\Models\Satker;
use App\Models\Profile;
use App\Models\UsulMutasi as ModelsUsulMutasi;
use Livewire\Component;
use Illuminate\Validation\Rule;

class UsulMutasi extends Component
{
    public $nip, $nama, $jabatan, $satker, $alasan;
    public $jenis = 1;
    public $suggestionsNip = [];
    public $suggestionsSatker = [];

    public function updatedNip($value)
    {
        if (empty($value)) {
            $this->suggestionsNip = []; // Kosongkan suggestion jika input NIP kosong
            $this->nama = ''; // Reset nama
            $this->jabatan = ''; // Reset jabatan
        } else {
            // Cari suggestion NIP
            $this->suggestionsNip = Profile::where('nip', 'like', '%' . $value . '%')
                ->distinct()
                ->limit(5)
                ->pluck('nip')
                ->toArray();
        }
    }

    public function selectNip($nip)
    {
        $profile = Profile::where('nip', $nip)->first();
        if ($profile) {
            $this->nip = $nip;
            $this->nama = $profile->nama;
            $this->jabatan = $profile->jabatan;
        }
        $this->suggestionsNip = []; // Kosongkan suggestion setelah memilih
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
            'nip'  => ['required', Rule::exists('profile', 'nip')],
            'satker'  => ['required', Rule::exists('satker', 'nama')],
            'jabatan' => 'required|string|max:255',
            'jenis' => 'required|integer',
            'alasan' => 'required',
        ]);

        // Simpan ke database
        ModelsUsulMutasi::create([
            'nip' => $this->nip,
            'satker_tujuan' => Satker::where('nama', $this->satker)->value('id'),
            'jenis' => $this->jenis,
            'alasan' => $this->alasan,
        ]);

        $this->dispatch('refreshTable');

        // Reset form
        $this->reset(['nip', 'nama', 'jabatan', 'satker', 'jenis', 'alasan']);

        $this->dispatch('close-modal');

        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Usul Mutasi Berhasil Ditambahkan!', 'success');
    }

    public function render()
    {
        return view('livewire.mutasi.usul.usul-mutasi')->extends('layouts.app');
    }
}
