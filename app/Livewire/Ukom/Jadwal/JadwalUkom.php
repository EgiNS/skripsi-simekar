<?php

namespace App\Livewire\Ukom\Jadwal;

use Carbon\Carbon;
use App\Models\Ukom;
use Livewire\Component;
use Livewire\Attributes\On;

class JadwalUkom extends Component
{
    public $events = [];
    public $judul, $tanggal_mulai, $tanggal_akhir;
    public $showModal = false;
    public $editId, $editTitle, $editStart, $editEnd;

    protected $listeners = ['openEditModal'];

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
                'id'    => $ukom->id,
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

        $this->dispatch('showFlashMessage', 'Jadwal Berhasil Ditambahkan!', 'success');
    }

    #[On('openEditModal')]
    public function openEditModal($id, $title, $start, $end)
    {
        $this->editId = $id;
        $this->editTitle = $title;
        $this->editStart = substr($start, 0, 10);
        $this->editEnd = date('Y-m-d', strtotime($end . ' -1 day'));

        $this->dispatch('show-edit-modal');
    }

    public function updateEvent()
    {
        Ukom::find($this->editId)->update([
            'judul' => $this->editTitle,
            'tanggal_mulai' => $this->editStart,
            'tanggal_akhir' => $this->editEnd,
        ]);

        // Load ulang event
        $events = Ukom::all()->map(function ($ukom) {
            $tanggalAkhir = $ukom->tanggal_akhir ?? $ukom->tanggal_mulai;
            return [
                'id' => $ukom->id,
                'title' => $ukom->judul,
                'start' => $ukom->tanggal_mulai,
                'end' => date('Y-m-d', strtotime($ukom->tanggal_akhir . ' +1 day')),
                'color' => now()->greaterThan($tanggalAkhir) ? '#BFBFBF' : ''
            ];
        });

        $this->dispatch('calendar-refresh', $events);
        $this->dispatch('hide-edit-modal');
        $this->dispatch('showFlashMessage', 'Jadwal Berhasil Diubah!', 'success');
    }

    public function deleteEvent()
    {
        // Pastikan ada ID yang akan dihapus
        if ($this->editId) {
            Ukom::find($this->editId)?->delete(); // Gunakan model kamu

            $events = Ukom::all()->map(function ($ukom) {
                $tanggalAkhir = $ukom->tanggal_akhir ?? $ukom->tanggal_mulai;
                return [
                    'id' => $ukom->id,
                    'title' => $ukom->judul,
                    'start' => $ukom->tanggal_mulai,
                    'end' => date('Y-m-d', strtotime($ukom->tanggal_akhir . ' +1 day')),
                    'color' => now()->greaterThan($tanggalAkhir) ? '#BFBFBF' : ''
                ];
            });

            $this->dispatch('calendar-refresh', $events);
            $this->dispatch('hide-edit-modal');

            $this->dispatch('showFlashMessage', 'Jadwal Berhasil Dihapus!', 'success');
        }
    }

    public function render()
    {
        return view('livewire.ukom.jadwal.jadwal-ukom')->extends('layouts.app');
    }
}
