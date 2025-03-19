<?php

namespace App\Livewire\Ukom\Informasi;

use App\Models\InfoUkom;
use Livewire\Component;

class TambahInformasiUkom extends Component
{
    public $judul;
    public $isi = '';

    public function createInfo()
    {
        $validatedData = $this->validate([
            'judul' => 'required',
            'isi' => 'required',
        ]);

        // Simpan ke database
        InfoUkom::create([
            'judul' => $this->judul,
            'isi' => $this->isi,
        ]);

        // Reset form
        $this->reset(['judul', 'isi']);
        
        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Informasi Ukom Berhasil Ditambahkan!', 'success');
        
        $this->dispatch('navigateTo', '/info-ukom');
    }

    public function render()
    {
        return view('livewire.ukom.informasi.tambah-informasi-ukom')->extends('layouts.app');
    }
}