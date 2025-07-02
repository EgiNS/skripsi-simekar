<?php

namespace App\Livewire\Ukom\Pegawai;

use Livewire\Component;
use App\Models\InfoUkom;

class DetailInformasiUkom extends Component
{
    public $info;

    public function mount($id)
    {
        $this->info = InfoUkom::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.ukom.pegawai.detail-informasi-ukom')->extends('layouts.user');
    }
}
