<?php

namespace App\Livewire\AngkaKredit\Daftar;

use Livewire\Component;

class DaftarAngkaKredit extends Component
{
    public function render()
    {
        return view('livewire.angka-kredit.daftar.daftar-angka-kredit')->extends('layouts.app');
    }
}
