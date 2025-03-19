<?php

namespace App\Livewire\MinatKarir;

use Livewire\Component;

class TesMinatKarier extends Component
{
    public $step = 1;
    public $selectedOptions = [];

    public function nextPage()
    {
        $this->step++;
        $this->selectedOptions = []; // Reset pilihan saat pindah step
    }

    public function prevPage()
    {
        $this->step--;
        $this->selectedOptions = []; // Reset pilihan saat pindah step
    }

    public function render()
    {
        return view('livewire.minat-karir.tes-minat-karier')->extends('layouts.user');
    }
}
