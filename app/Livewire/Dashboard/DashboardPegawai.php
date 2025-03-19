<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class DashboardPegawai extends Component
{
    public function render()
    {
        return view('livewire.dashboard.dashboard-pegawai')->extends('layouts.user');
    }
}
