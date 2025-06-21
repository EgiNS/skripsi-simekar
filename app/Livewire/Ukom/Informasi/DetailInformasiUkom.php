<?php

namespace App\Livewire\Ukom\Informasi;

use Livewire\Component;
use App\Models\InfoUkom;
use Illuminate\Support\Facades\Auth;

class DetailInformasiUkom extends Component
{
    public $info;

    public function mount($id)
    {
        $this->info = InfoUkom::findOrFail($id);
    }

    public function render()
    {
        if (Auth::user()->role == 3) {
            return view('livewire.ukom.informasi.detail-informasi-ukom')->extends('layouts.user');
        } else {
            return view('livewire.ukom.informasi.detail-informasi-ukom')->extends('layouts.app');
        }
    }
}
