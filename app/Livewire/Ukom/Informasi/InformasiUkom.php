<?php

namespace App\Livewire\Ukom\Informasi;

use App\Models\InfoUkom;
use Livewire\Component;

class InformasiUkom extends Component
{
    public $info;

    public function mount()
    {
        $this->info = InfoUkom::all();
    }

    public function render()
    {
        return view('livewire.ukom.informasi.informasi-ukom')->extends('layouts.app');
    }
}
