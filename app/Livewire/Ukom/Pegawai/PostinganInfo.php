<?php

namespace App\Livewire\Ukom\Pegawai;

use Livewire\Component;
use App\Models\InfoUkom;

class PostinganInfo extends Component
{
    public $info;

    public function mount()
    {
        $this->info = InfoUkom::all();
    }

    public function render()
    {
        return view('livewire.ukom.pegawai.postingan-info')->extends('layouts.user');
    }
}
