<?php

namespace App\Livewire\Karier;

use Livewire\Component;

class RekomendasiKarier extends Component
{
    public function render()
    {
        return view('livewire.karier.rekomendasi-karier')->extends('layouts.user');
    }
}
