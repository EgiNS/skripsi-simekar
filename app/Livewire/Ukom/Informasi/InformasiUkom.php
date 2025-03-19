<?php

namespace App\Livewire\Ukom\Informasi;

use App\Models\InfoUkom;
use Livewire\Component;

class InformasiUkom extends Component
{
    public $info,$hapusId;
    public $showModalDelete = false;

    public function mount()
    {
        $this->info = InfoUkom::all();
    }

    public function openModalDelete($id)
    {
        $this->hapusId = $id;
        $this->showModalDelete = true;
    }

    public function delete()
    {
        InfoUkom::find($this->hapusId)->delete();
        $this->info = $this->info->except($this->hapusId);
        $this->showModalDelete = false; 
        $this->dispatch('showFlashMessage', 'Postingan berhasil dihapus!', 'success');
    }

    public function render()
    {
        return view('livewire.ukom.informasi.informasi-ukom')->extends('layouts.app');
    }
}
