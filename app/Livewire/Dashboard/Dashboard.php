<?php

namespace App\Livewire\Dashboard;

use App\Models\ABK;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $dataSatker;
    public $monthData = [
        'Januari' => [
            ['satker' => 'Satker A', 'count' => 10],
            ['satker' => 'Satker B', 'count' => 5],
        ],
        'Februari' => [
            ['satker' => 'Satker C', 'count' => 7],
            ['satker' => 'Satker D', 'count' => 8],
        ],
        'Maret' => [
            ['satker' => 'Satker E', 'count' => 6],
        ],
        'April' => [
            ['satker' => 'Satker F', 'count' => 9],
            ['satker' => 'Satker G', 'count' => 12],
        ],
        'Mei' => [
            ['satker' => 'Satker H', 'count' => 4],
            ['satker' => 'Satker I', 'count' => 3],
        ],
        'Juni' => [
            ['satker' => 'Satker J', 'count' => 11],
        ],
        'Juli' => [
            ['satker' => 'Satker K', 'count' => 5],
            ['satker' => 'Satker L', 'count' => 7],
        ],
        'Agustus' => [
            ['satker' => 'Satker M', 'count' => 6],
            ['satker' => 'Satker N', 'count' => 8],
        ],
        'September' => [
            ['satker' => 'Satker O', 'count' => 9],
        ],
        'Oktober' => [
            ['satker' => 'Satker P', 'count' => 10],
            ['satker' => 'Satker Q', 'count' => 3],
        ],
        'November' => [
            ['satker' => 'Satker R', 'count' => 12],
        ],
        'Desember' => [
            ['satker' => 'Satker S', 'count' => 7],
            ['satker' => 'Satker T', 'count' => 6],
        ],
    ];

    public function mount()
    {
        $this->dataSatker = ABK::select(
            'abk.id_satker', 
            'satker.nama', // Ambil nama satker dari tabel Satker
            DB::raw('SUM(formasi) as formasi'),
            DB::raw('(SELECT COUNT(*) FROM profile WHERE profile.id_satker = abk.id_satker) as eksisting')
        )
        ->join('satker', 'satker.id', '=', 'abk.id_satker') // Join ke tabel Satker
        ->groupBy('abk.id_satker', 'satker.nama') // Group by harus menyertakan semua kolom non-agregat
        ->get();    
    }
    
    public function render()
    {
        return view('livewire.dashboard.dashboard')->extends('layouts.app');
    }
}
