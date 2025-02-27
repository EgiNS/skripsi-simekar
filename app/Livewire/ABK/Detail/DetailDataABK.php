<?php

namespace App\Livewire\Abk\Detail;

use App\Models\Profile;
use Livewire\Component;

class DetailDataABK extends Component
{
    public function render()
    {
        return view('livewire.abk.detail.detail-data-a-b-k')
            ->extends('layouts.app');
    }
}
