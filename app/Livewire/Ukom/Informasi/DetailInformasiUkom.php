<?php

namespace App\Livewire\Ukom\Informasi;

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
        return view('livewire.ukom.informasi.detail-informasi-ukom')->extends('layouts.app');
    }
}
