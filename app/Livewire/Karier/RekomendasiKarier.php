<?php

namespace App\Livewire\Karier;

use Carbon\Carbon;
use App\Models\ABK;
use App\Models\Satker;
use App\Models\Profile;
use Livewire\Component;
use App\Models\Golongan;
use App\Models\AngkaKredit;
use App\Models\RekomKarier;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RekomendasiKarier extends Component
{
    public $user, $isFungsional, $isAk;
    public $nextJabatan;
    public $rekom, $akMinimal, $rumpun;
    public $formasiSaatIni;
    public $formasiNextJenjang;
    public $perkiraan_kp, $perkiraan_kj, $periode_kp, $all_pred;

    public function mount()
    {
        $this->user = Profile::where(['username'=>Auth::user()->username, 'active'=>1])->first();

        $keywords = [
            'terampil', 'mahir', 'penyelia',
            'ahli pertama', 'ahli muda', 'ahli madya', 'ahli utama'
        ];

        $jabatan = strtolower($this->user->jabatan); // pastikan lowercase

        // Cek apakah jabatan mengandung salah satu keyword
        $this->isFungsional = collect($keywords)->contains(function ($keyword) use ($jabatan) {
            return Str::contains($jabatan, strtolower($keyword));
        });

        $this->isAk = AngkaKredit::where('nip', $this->user->nip)->orderBy('id', 'desc')->first();

        if ($this->isFungsional && $this->isAk) {
            $this->nextJabatan = $this->getNextJenjang($this->user->jabatan);
            $this->rekom = $this->getSyaratRekomendasi($this->user->jabatan);
            if ($this->rekom) {
                $this->akMinimal = $this->getAngkaKredit($this->user->jabatan, $this->rekom->syarat);
            }
            $this->formasiSaatIni = $this->loadFormasiTersedia($this->user->jabatan);
            $this->formasiNextJenjang = $this->loadFormasiTersedia($this->nextJabatan);
            $this->getPrediksi();
        }

    }

    public function getNextJenjang($jabatan)
    {
        $jenjangList = [
            'terampil',
            'mahir',
            'penyelia',
            'ahli pertama',
            'ahli muda',
            'ahli madya',
            'ahli utama',
        ];

        // Ubah ke lowercase biar konsisten
        $jabatanLower = strtolower($jabatan);

        // Cari jenjang yang ada di dalam string jabatan
        $jenjangSaatIni = null;
        foreach ($jenjangList as $jenjang) {
            if (str_contains($jabatanLower, $jenjang)) {
                $jenjangSaatIni = $jenjang;
                break;
            }
        }

        if (!$jenjangSaatIni) {
            return null; // tidak ditemukan jenjang
        }

        // Ambil posisi sekarang dan next
        $currentIndex = array_search($jenjangSaatIni, $jenjangList);
        $nextIndex = $currentIndex + 1;

        if (!isset($jenjangList[$nextIndex])) {
            return null; // tidak ada jenjang berikutnya
        }

        // Ambil bagian nama jabatan tanpa jenjang
        $namaJabatan = trim(str_replace($jenjangSaatIni, '', $jabatanLower));

        $this->rumpun = $namaJabatan;

        // Capitalize hasil akhir (optional)
        $nextJabatan = ucwords(trim($namaJabatan . ' ' . $jenjangList[$nextIndex]));

        return $nextJabatan;
    }

    public function getSyaratRekomendasi($jabatanLengkap)
    {
        $jenjangList = [
            'terampil',
            'mahir',
            'penyelia',
            'ahli pertama',
            'ahli muda',
            'ahli madya',
            'ahli utama',
        ];

        $jabatanLower = strtolower($jabatanLengkap);
        $namaJabatan = $jabatanLower;

        // Hilangkan jenjang dari string jabatan
        foreach ($jenjangList as $jenjang) {
            if (str_contains($jabatanLower, $jenjang)) {
                $namaJabatan = str_replace($jenjang, '', $jabatanLower);
                break;
            }
        }

        $namaJabatan = ucwords(trim($namaJabatan)); // kapitalisasi dan trimming

        // Cari data RekomKarier
        $rekom = RekomKarier::where('jabatan', $namaJabatan)->first();

        return $rekom;
    }

    public function getAngkaKredit($jabatanLengkap, $jsonSyarat)
    {
        $ak_jenjang = [
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

        return $ak_jenjang[$this->user->golongan->nama];
    }

    public function getEksisting($jabatan, $satker)
    {
        return Profile::where(['jabatan'=>$jabatan, 'id_satker'=>$satker, 'active'=>1])
            ->count();
    }

    public function getPrediksi()
    {
        $ak_jenjang = [
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

        $gol_jenjang = [
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

        $nilaiJenjang = [
            'terampil' => 5,
            'mahir' => 12.5,
            'ahli pertama' => 12.5,
            'penyelia' => 25,
            'ahli muda' => 25,
            'ahli madya' => 37.5,
            'ahli utama' => 50,
        ];

        $ak = AngkaKredit::where('nip', $this->user->nip)->orderBy('id', 'desc')->first();

        $ak_kp = $this->user->golongan->ak_minimal;
        $ak_kj = isset($ak_jenjang[$this->user->golongan->nama]) ? $ak_jenjang[$this->user->golongan->nama] : '-';

        $jenjang = $gol_jenjang[$this->user->golongan->nama][0];
        $ak_tahunan = $nilaiJenjang[$jenjang];

        $pred_kp = ceil(($ak_kp - $ak->total_ak) / $ak_tahunan * 12);
        $pred_kj = ceil(($ak_kj - $ak->total_ak) / $ak_tahunan * 12);

        $this->perkiraan_kp = Carbon::parse($ak->periode_end)->startOfMonth()->addMonths($pred_kp)->translatedFormat('F Y');
        $this->perkiraan_kj = Carbon::parse($ak->periode_end)->startOfMonth()->addMonths($pred_kj)->translatedFormat('F Y');

        $perkiraan_kp_carb = Carbon::parse($ak->periode_end)->startOfMonth()->addMonths($pred_kp);
        $this->periode_kp = $this->getPeriodeDanDeadline($perkiraan_kp_carb);

        $this->all_pred = $this->getPerkiraanKPBerikutnya($this->user->golongan->nama, $perkiraan_kp_carb, $gol_jenjang);
    }

    public function getPerkiraanKPBerikutnya(string $currentGol, Carbon $perkiraan_kp, array $gol_jenjang): array
    {
        $found = false;
        $result = [];
        $currentPerkiraan = $perkiraan_kp->copy();
    
        foreach ($gol_jenjang as $gol => $jenjangs) {
            if ($gol === $currentGol) {
                $found = true;
            }
    
            if ($found) {
                $result[$gol] = [
                    'gol' => $gol,
                    'perkiraan_kp' => $currentPerkiraan->translatedFormat('F Y'),
                    'jenjang' => ucwords(trim($this->rumpun . ' ' . $jenjangs[0])),
                    'ak_min' => Golongan::where('nama', $gol)->value('ak_minimal') ?? '-'
                ];
                $currentPerkiraan = $currentPerkiraan->copy()->addYears(4);
            }
        }
    
        return $result;
    }
    

    // public function getPeriodeDanDeadline(Carbon $perkiraan_kp): array
    // {
    //     $periode = [
    //         'Februari' => '15 December',
    //         'April'    => '15 February',
    //         'Juni'     => '15 April',
    //         'Agustus'  => '15 June',
    //         'Oktober'  => '15 August',
    //         'Desember' => '15 October',
    //     ];
    
    //     $today = Carbon::now();
    //     $year  = $perkiraan_kp->year;
    
    //     foreach ($periode as $bulan => $deadline) {
    //         $monthMap = [
    //             'Februari' => 2,
    //             'April'    => 4,
    //             'Juni'     => 6,
    //             'Agustus'  => 8,
    //             'Oktober'  => 10,
    //             'Desember' => 12,
    //         ];
    
    //         $periodeBulan = Carbon::createFromDate($year, $monthMap[$bulan], 1);
    
    //         // Adjust deadline year: Desember's deadline is in the previous year
    //         $deadlineYear = ($bulan === 'Februari') ? $year - 1 : $year;
    //         $deadlineDate = Carbon::parse("{$deadline} {$deadlineYear}");
   
    //         if ($perkiraan_kp->lessThanOrEqualTo($periodeBulan) && $perkiraan_kp->lessThan($deadlineDate)) {
    //             return [
    //                 'periode' => "$bulan $year",
    //                 'deadline' => $deadlineDate->translatedFormat('d F Y'),
    //             ];
    //         }
    //     }
    
    //     // Jika tidak ada yang cocok, berikan periode berikutnya tahun depan
    //     $nextYear = $year + 1;
    //     return [
    //         'periode' => "Februari $nextYear",
    //         'deadline' => Carbon::parse(($nextYear - 1) . '-12-15')->translatedFormat('d F Y'),
    //     ];
    // }

    public function getPeriodeDanDeadline(Carbon $perkiraan_kp): array
    {
        $periode = [
            'Februari' => '15 December',
            'April'    => '15 February',
            'Juni'     => '15 April',
            'Agustus'  => '15 June',
            'Oktober'  => '15 August',
            'Desember' => '15 October',
        ];

        $today = Carbon::now();

        // Gunakan acuan sekarang jika perkiraan sudah lewat
        $acuan = $perkiraan_kp->greaterThanOrEqualTo($today) ? $perkiraan_kp : $today;
        $year = $acuan->year;

        foreach ($periode as $bulan => $deadline) {
            $monthMap = [
                'Februari' => 2,
                'April'    => 4,
                'Juni'     => 6,
                'Agustus'  => 8,
                'Oktober'  => 10,
                'Desember' => 12,
            ];

            $periodeBulan = Carbon::createFromDate($year, $monthMap[$bulan], 1);

            // Deadline biasanya di tahun yang sama, kecuali Februari → Desember tahun sebelumnya
            $deadlineYear = $bulan === 'Februari' ? $year - 1 : $year;
            $deadlineDate = Carbon::parse("{$deadline} {$deadlineYear}");

            // LOGIKA KHUSUS DESEMBER
            if (
                $acuan->month === 12 &&
                $acuan->greaterThan(Carbon::createFromDate($acuan->year, 12, 15))
            ) {
                // Setelah 15 Desember → masuk periode APRIL tahun depan
                $periodeBulan = Carbon::createFromDate($year + 1, 4, 1);
                $deadlineDate = Carbon::createFromDate($year + 1, 2, 15);
                return [
                    'periode' => "April " . ($year + 1),
                    'deadline' => $deadlineDate->translatedFormat('d F Y'),
                ];
            }

            if ($acuan->lessThanOrEqualTo($periodeBulan) && $acuan->lessThan($deadlineDate)) {
                return [
                    'periode' => "$bulan $year",
                    'deadline' => $deadlineDate->translatedFormat('d F Y'),
                ];
            }
        }

        // Jika tidak cocok, fallback ke Februari tahun depan
        $nextYear = $year + 1;
        return [
            'periode' => "Februari $nextYear",
            'deadline' => Carbon::createFromDate($year, 12, 15)->translatedFormat('d F Y'),
        ];
    }

    public function loadFormasiTersedia($jabatanTarget)
    {
        $rows = ABK::where('jabatan', $jabatanTarget)->get();
        $formasiAvail = [];

        foreach ($rows as $row) {
            $eksisting = $this->getEksisting($row->jabatan, $row->id_satker);
            $selisih = $row->formasi - $eksisting;

            if ($selisih > 0) {
                $namaSatker = Satker::find($row->id_satker)->nama ?? 'Satker Tidak Diketahui';
                $formasiAvail[] = [
                    'nama' => $namaSatker,
                    'formasi' => $selisih,
                ];
                
            }
        }

        return $formasiAvail;
    }

    public function render()
    {
        if (!$this->isAk || $this->isAk->status == 3) {
            return view('livewire.karier.nonakredit')->extends('layouts.user');
        } elseif ($this->isFungsional) {
            return view('livewire.karier.rekomendasi-karier')->extends('layouts.user');
        } else {
            return view('livewire.karier.nonfungsional')->extends('layouts.user');
        }
    }
}
