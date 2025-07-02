<?php

namespace App\Livewire\MinatKarir;

use App\Models\HasilTesMinatKarier;
use Carbon\Carbon;
use App\Models\Profile;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\TesMinatKarier as ModelsTesMinatKarier;
use Illuminate\Support\Facades\Auth;

class TesMinatKarier extends Component
{
    public $soalPerPage = 7;
    public $currentPage = 1;
    public $totalPages;
    public $selectedOptions = [];
    public $user, $exist;
    public $hasil, $riwayat;

    public function mount()
    {
        $this->user = Profile::where(['username'=>Auth::user()->username, 'active'=>1])->first();
        $this->riwayat = HasilTesMinatKarier::where('nip', $this->user->nip)->get();
        $this->exist = ModelsTesMinatKarier::all()->isEmpty();
    }

    public function getSoalPageProperty()
    {
        if ($this->currentPage < 2 || $this->currentPage > $this->getLastSoalPage() + 1) {
            return collect(); // tidak tampilkan soal di halaman pengantar & konfirmasi
        }
    
        return ModelsTesMinatKarier::skip(($this->currentPage - 2) * $this->soalPerPage)
            ->take($this->soalPerPage)
            ->get();
    }
    
    public function nextPage()
    {
        $this->storeCurrentSelections(); // simpan dulu pilihan sebelum lanjut
        $maxPage = $this->getLastSoalPage() + 2;
    
        if ($this->currentPage < $maxPage) {
            $this->currentPage++;
        }
    }

    private function getLastSoalPage()
    {
        $totalSoal = ModelsTesMinatKarier::count();
        return ceil($totalSoal / $this->soalPerPage);
    }
    
    public function prevPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }    

    public function storeCurrentSelections()
    {
        // tidak perlu isi di sini jika nanti pakai entri realtime via wire:model.defer (bisa dikembangkan)
    }

    public function finishTest()
    {
        // 1. Gabungkan semua jawaban user
        $allSelectedIds = collect($this->selectedOptions)
            ->flatten()
            ->filter()
            ->values();

        if ($allSelectedIds->isEmpty()) {
            session()->flash('error', 'Anda belum memilih pernyataan apapun.');
            return;
        }

        $totalPilihan = collect($this->selectedOptions)->flatten()->count();

        // 2. Ambil jabatan dari soal yang dipilih
        $jabatanCounts = ModelsTesMinatKarier::whereIn('id', $allSelectedIds)
            ->select('jabatan')
            ->get()
            ->groupBy('jabatan')
            ->map(function ($items, $jabatanId) use ($totalPilihan) {
                return [
                    'jabatan' => $jabatanId,
                    'total' => round(($items->count() / $totalPilihan) * 100, 0),
                ];
            })
            ->sortByDesc('total')
            ->take(3)
            ->values();

        $this->hasil = $jabatanCounts;

        $this->currentPage++;

        DB::table('hasil_tes_minat_karier')->insert([
            'nip' => $this->user->nip,
            'jabatan_1' => $jabatanCounts[0]['jabatan'],
            'total_1' => $jabatanCounts[0]['total'],
            'jabatan_2' => $jabatanCounts[1]['jabatan'],
            'total_2' => $jabatanCounts[1]['total'],
            'jabatan_3' => $jabatanCounts[2]['jabatan'],
            'total_3' => $jabatanCounts[2]['total'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function render()
    {
        return view('livewire.minat-karir.tes-minat-karier')->extends('layouts.user');
    }
}
