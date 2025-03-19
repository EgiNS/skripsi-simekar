<?php

namespace App\Livewire\Ukom\Pegawai;

use App\Models\Ukom;
use Livewire\Component;

class InfoUkom extends Component
{
    public $events = [];

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = Ukom::all()->map(function ($ukom) {
            $tanggalAkhir = $ukom->tanggal_akhir ?? $ukom->tanggal_mulai; // Gunakan tanggal_mulai jika tidak ada tanggal_akhir
            $sudahSelesai = now()->greaterThan($tanggalAkhir);

            return [
                'title' => $ukom->judul,
                'start' => $ukom->tanggal_mulai,
                'end' => date('Y-m-d', strtotime($ukom->tanggal_akhir . ' +1 day')), // FullCalendar butuh end+1 hari
                'color' => $sudahSelesai ? '#BFBFBF' : ''
            ];
        });

        $this->dispatch('initCalendar', ['events' => $this->events]);
    }

    public function render()
    {
        return view('livewire.ukom.pegawai.info-ukom')->extends('layouts.user');
    }
}
