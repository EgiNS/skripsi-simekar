<?php

namespace App\Livewire\Karier;

use App\Models\Jabatan;
use Livewire\Component;
use App\Models\RekomKarier;

class EditRekomKarier extends Component
{
    public $jabatan = '';
    public $id;
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

    public function mount($id)
    {
        $data = RekomKarier::findOrFail($id);
        // dd($data);
        $this->id = $id;
        $this->jabatan = $data->jabatan;
        $this->rekomendasi = $data->rekomendasi;

        $this->syaratList = $data->syarat['syarat'] ?? [''];
        $this->angkaKredit = $data->syarat['angka_kredit'] ?? [
            'terampil' => '',
            'mahir' => '',
            'ahli_pertama' => '',
            'ahli_muda' => '',
            'ahli_madya' => '',
        ];
    }

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

    public function save()
    {
        $data = RekomKarier::find($this->id);

        $data->jabatan = $this->jabatan;
        $data->rekomendasi = $this->rekomendasi;
        $data->syarat = [
            'angka_kredit' => $this->angkaKredit,
            'syarat' => $this->syaratList,
        ];
        $data->save();
        
        $this->dispatch('showFlashMessage', 'Perubahan berhasil disimpan', 'success');
    }

    public function render()
    {
        return view('livewire.karier.edit-rekom-karier')->extends('layouts.app');
    }
}
