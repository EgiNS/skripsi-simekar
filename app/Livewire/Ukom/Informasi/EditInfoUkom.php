<?php

namespace App\Livewire\Ukom\Informasi;

use Livewire\Component;
use App\Models\InfoUkom;

class EditInfoUkom extends Component
{
    public $judul, $id;
    public $isi = '';

    public function mount($id)
    {
        $info = InfoUkom::findOrFail($id);
        $this->judul = $info->judul;
        $this->isi = $info->isi;
    }

    public function saveEdit()
    {
        $data = InfoUkom::find($this->id);
        $data->judul = $this->judul;
        $data->isi = $this->isi;
        $data->save();

        $this->dispatch('navigateTo', '/info-ukom');

        $this->dispatch('showFlashMessage', 'Informasi Ukom Berhasil Diubah!', 'success');
    }

    public function render()
    {
        return view('livewire.ukom.informasi.edit-info-ukom')->extends('layouts.app');
    }
}
