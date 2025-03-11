<?php

namespace App\Livewire\Ukom\Jadwal;

use Carbon\Carbon;
use App\Models\Ukom;
use Livewire\Component;

class JadwalUkom extends Component
{
    public $events = [];
    public $judul, $tanggal_mulai, $tanggal_akhir;
    public $showModal = false;

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

    public function tambahEvent()
    {
        $this->validate([
            'judul' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        Ukom::create([
            'judul' => $this->judul,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_akhir' => $this->tanggal_akhir,
        ]);

        // Kirim hanya event yang baru saja dibuat ke JavaScript
        $this->dispatch('eventAdded', [
            'title' => $this->judul,
            'start' => Carbon::parse($this->tanggal_mulai)->format('Y-m-d'), 
            'end' => Carbon::parse($this->tanggal_akhir)->addDay()->format('Y-m-d'),
        ]);

        $this->showModal = false;
        
        // Reset form & tutup modal
        $this->reset(['judul', 'tanggal_mulai', 'tanggal_akhir']);
    }

    public function render()
    {
        return view('livewire.ukom.jadwal.jadwal-ukom')->extends('layouts.app');
    }
}
