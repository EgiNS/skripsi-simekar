<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\ABK;
use App\Models\Ukom;
use App\Models\Profile;
use Livewire\Component;
use App\Models\UsulMutasi;
use App\Models\AngkaKredit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $user;
    public $dataSatker;
    public $groupedData = [];
    public $tahun, $ukom;

    public array $nilaiJenjang = [
        'terampil' => 5,
        'mahir' => 12.5,
        'ahli pertama' => 12.5,
        'penyelia' => 25,
        'ahli muda' => 25,
        'ahli madya' => 37.5,
        'ahli utama' => 50,
    ];

    public array $gol_jenjang = [
        'II/c' => ['terampil'],
        'II/d' => ['terampil'],
        'III/a' => ['ahli pertama', 'mahir'],
        'III/b' => ['ahli pertama', 'mahir'],
        'III/c' => ['ahli muda', 'penyelia'],
        'III/d' => ['ahli muda', 'penyelia'],
        'IV/a' => ['ahli madya'],
        'IV/b' => ['ahli madya'],
        'IV/c' => ['ahli madya'],
        'IV/d' => ['ahli utama'],
        'IV/e' => ['ahli utama'],
    ]; 

    public array $ak_jenjang = [
        'II/c' => 40,
        'II/d' => 40,
        'III/a' => 100,
        'III/b' => 100,
        'III/c' => 200,
        'III/d' => 200,
        'IV/a' => 450,
        'IV/b' => 450,
        'IV/c' => 450,
    ]; 

    public function mount()
    {
        $this->user = Profile::where(['username'=>Auth::user()->username, 'active'=>1])->first();
        $this->tahun = now()->year;
        $this->getNaikPangkat();
        $this->setDataSatker();
        Carbon::setLocale('id');
        $this->ukom = Ukom::latest()->take(5)->get()->sortBy('id')->values();
    }

    // Method untuk menangani perubahan tahun
    public function updatedTahun($tahun)
    {
        // Update tahun yang dipilih
        $this->tahun = $tahun;

        // Panggil ulang data dan hitung angka kredit
        $this->getNaikPangkat();
        $this->setDataSatker(); // Ambil data satker berdasarkan tahun baru
    }

    public function setDataSatker()
    {
        $this->dataSatker = ABK::select(
            'abk.id_satker',
            'satker.nama', // Ambil nama satker dari tabel Satker
            DB::raw('SUM(formasi) as formasi'),
            DB::raw('(SELECT COUNT(*) FROM profile WHERE profile.id_satker = abk.id_satker AND profile.active = 1) as eksisting')
        )
        ->join('satker', 'satker.id', '=', 'abk.id_satker') // Join ke tabel Satker
        ->groupBy('abk.id_satker', 'satker.nama') // Group by harus menyertakan semua kolom non-agregat
        ->get();
    }
    
    public function getNaikPangkat() {
        $all_ak = AngkaKredit::all();
        $groupedData = [];
    
        $currentYear = now()->year; // Tahun sekarang
        $maxYear = $currentYear + 3; // 3 tahun ke depan
    
        // Daftar bulan genap dalam Bahasa Indonesia
        $bulanGenap = [
            'Februari', 'April', 'Juni', 'Agustus', 'Oktober', 'Desember'
        ];
    
        // Loop untuk setiap tahun yang dipilih
        foreach ($bulanGenap as $bulan) {
            // Mengisi data untuk tahun yang dipilih dengan null untuk setiap bulan
            $groupedData[$this->tahun][$bulan] = null;
        }
    
        foreach ($all_ak as $ak) {
            $satker = $ak->profile->satker->nama;
            $ak_kp = $ak->profile->golongan->ak_minimal;
            $jenjang = $this->gol_jenjang[$ak->profile->golongan->nama][0];
            $ak_tahunan = $this->nilaiJenjang[$jenjang];
    
            $ak_total = $ak->total_ak ?? 0;
            $periode_end = $ak->periode_end;
    
            $pred_kp = ceil(($ak_kp - $ak_total) / $ak_tahunan * 12);
            $perkiraan_kp = Carbon::parse($periode_end)->startOfMonth()->addMonths($pred_kp);
    
            // Pastikan bulan genap
            if ($perkiraan_kp->month % 2 !== 0) {
                $perkiraan_kp->addMonth();
            }
    
            $tahun = $perkiraan_kp->format('Y');
            $bulan = $perkiraan_kp->translatedFormat('F'); // e.g. "February"
    
           // Hanya simpan data jika tahun perkiraan antara tahun sekarang dan 3 tahun ke depan
            if ($tahun >= $currentYear && $tahun <= $maxYear) {
                // Hanya simpan data untuk tahun yang dipilih dan bulan genap
                if ($tahun == $this->tahun && in_array($bulan, $bulanGenap)) {
                    // Inisialisasi array bulan jika belum ada
                    if (!isset($groupedData[$tahun][$bulan])) {
                        $groupedData[$tahun][$bulan] = [];
                    }

                    // Cek apakah satker sudah ada dalam array
                    $index = array_search($satker, array_column($groupedData[$tahun][$bulan], 'satker'));

                    if ($index !== false) {
                        // Jika satker sudah ada, tambahkan count
                        $groupedData[$tahun][$bulan][$index]['count'] += 1;
                    } else {
                        // Jika belum ada, tambahkan entry baru
                        $groupedData[$tahun][$bulan][] = [
                            'satker' => $satker,
                            'count' => 1,
                        ];
                    }
                }
            }
        }
    
        $this->groupedData = $groupedData; // Menyimpan data yang telah difilter

        // dd($this->groupedData);
    }
    
    public function getTotalCountBulanIni()
    {
        $carbon = now();

        // Jika hari sudah lewat tanggal 15, tambahkan 1 bulan
        if ($carbon->day > 15) {
            $carbon->addMonth();
        }
        
        // Jika hasil bulan adalah ganjil, tambahkan 1 bulan lagi
        if ($carbon->month % 2 !== 0) {
            $carbon->addMonth();
        }

        $bulan = $carbon->translatedFormat('F'); // e.g. "April"
        $tahun = $this->tahun;

        $total = 0;

        if (isset($this->groupedData[$tahun][$bulan])) {
            foreach ($this->groupedData[$tahun][$bulan] as $item) {
                $total += $item['count'];
            }
        }

        return $total;
    }

    public function getUsulMutasi()
    {
        $count = UsulMutasi::where('status', 1)->count();
        
        return $count;
    }

    public function getApprovalPAK()
    {
        $count = AngkaKredit::where('status', 1)->count();
        
        return $count;
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')->extends('layouts.app');
    }
}
