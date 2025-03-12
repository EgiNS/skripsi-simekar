<?php

namespace App\Livewire\Abk\Formasi;

use Livewire\Component;

class Formasi extends Component
{
    public function render()
    {
        return view('livewire.abk.formasi.formasi')->extends('layouts.user');
    }
}
