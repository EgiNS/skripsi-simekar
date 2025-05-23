<?php

namespace App\Livewire\Karier;

use App\Models\RekomKarier;
use Livewire\Component;

class AdminRekomKarier extends Component
{
    public $all;

    public function mount() {
        $this->all = RekomKarier::all();
    }

    public function render()
    {
        return view('livewire.karier.admin-rekom-karier')->extends('layouts.app');
    }
}
