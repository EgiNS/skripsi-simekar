<?php

namespace App\Livewire\Ukom\Informasi;

use Livewire\Component;

class TambahInformasiUkom extends Component
{
    public function render()
    {
        return view('livewire.ukom.informasi.tambah-informasi-ukom')->extends('layouts.app');
    }
}
