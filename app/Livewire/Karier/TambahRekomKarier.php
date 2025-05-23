<?php

namespace App\Livewire\Karier;

use App\Models\Jabatan;
use Livewire\Component;
use App\Models\RekomKarier;

class TambahRekomKarier extends Component
{
    public $jabatan = '';
    public $suggestions = [];
    public $syaratList = [''];
    public $rekomendasi;
    public $angkaKredit = [
        'terampil' => '',
        'mahir' => '',
        'ahli_pertama' => '',
        'ahli_muda' => '',
        'ahli_madya' => '',
    ];

    public function updatedJabatan()
    {
        $this->suggestions = Jabatan::where('nama_umum', 'like', '%' . $this->jabatan . '%')
            ->distinct()
            ->limit(5)
            ->pluck('nama_umum')
            ->toArray();
    }

    public function selectJabatan($jabatan)
    {
        $this->jabatan = $jabatan;
        $this->suggestions = [];
    }

    public function addSyarat()
    {
        $this->syaratList[] = '';
    }

    public function removeSyarat($index)
    {
        unset($this->syaratList[$index]);
        $this->syaratList = array_values($this->syaratList); // reset index agar berurutan
    }

    public function create()
    {
        $this->validate([
            'jabatan' => 'required|string|max:255',
            'angkaKredit.terampil' => 'nullable|numeric',
            'angkaKredit.mahir' => 'nullable|numeric',
            'angkaKredit.ahli_pertama' => 'nullable|numeric',
            'angkaKredit.ahli_muda' => 'nullable|numeric',
            'angkaKredit.ahli_madya' => 'nullable|numeric',
            'syaratList.*' => 'nullable|string|max:500',
            'rekomendasi' => 'required'
        ]);

        RekomKarier::create([
            'jabatan' => $this->jabatan,
            'syarat' => [
                'angka_kredit' => $this->angkaKredit,
                'syarat' => $this->syaratList,
            ],
            'rekomendasi' => $this->rekomendasi
        ]);

        $this->dispatch('navigateTo', '/karier');
        
        $this->dispatch('showFlashMessage', 'Rekomendasi Karier Berhasil Ditambahkan!', 'success');
    }
    
    public function render()
    {
        return view('livewire.karier.tambah-rekom-karier')->extends('layouts.app');
    }
}
