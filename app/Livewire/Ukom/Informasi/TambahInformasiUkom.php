<?php

namespace App\Livewire\Ukom\Informasi;

use Livewire\Component;
use App\Models\InfoUkom;
use Livewire\WithFileUploads;

class TambahInformasiUkom extends Component
{
    use WithFileUploads;
    
    public $judul;
    public $isi = '';
    public $files = [];

    public function createInfo()
    {
        $validatedData = $this->validate([
            'judul' => 'required',
            'isi' => 'required',
            'files.*' => 'file|mimes:pdf|max:5048'
        ]);

        $uploadedFiles = [];

        foreach ($this->files as $file) {
            $originalName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $originalName, 'public'); // Simpan dengan nama asli
            $uploadedFiles[] = $originalName; // Simpan hanya nama asli ke database
        }
        
        // Simpan ke database
        InfoUkom::create([
            'judul' => $this->judul,
            'isi' => $this->isi,
            'files' => json_encode($uploadedFiles, JSON_UNESCAPED_SLASHES),
        ]);

        // Reset form
        $this->reset(['judul', 'isi', 'files']);
        
        // Kirim notifikasi ke user
        $this->dispatch('showFlashMessage', 'Informasi Ukom Berhasil Ditambahkan!', 'success');
        
        $this->dispatch('navigateTo', '/info-ukom');
    }

    public function render()
    {
        return view('livewire.ukom.informasi.tambah-informasi-ukom')->extends('layouts.app');
    }
}