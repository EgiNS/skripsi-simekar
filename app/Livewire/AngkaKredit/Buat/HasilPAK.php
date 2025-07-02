<?php

namespace App\Livewire\AngkaKredit\Buat;

use COM;
use ZipArchive;
use Carbon\Carbon;
use App\Models\Satker;
use App\Models\Jabatan;
use App\Models\Profile;
use Livewire\Component;
use App\Models\Golongan;
use ZipStream\ZipStream;
use App\Models\AngkaKredit;
use Livewire\WithPagination;
use ZipStream\Option\Archive;
use App\Models\PakNomorTracking;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HasilPAK extends Component
{
    use WithPagination;

    public array $inputs = [];
    public $jenis;
    public $jenis_angkat_kembali = '';
    public $predikat, $golongan, $jenjang_tujuan;
    public $angka_kredit, $angka_kredit_awal, $jft_sebelum;
    public $ak_before_tb, $lama_tb, $ak_jft;
    public $mulai_periode, $akhir_periode;
    public $selectedNip;
    public $page = 1;
    protected $queryString = ['page'];

    public array $persenPredikat = [
        'Sangat Kurang' => 0.25,
        'Kurang' => 0.5,
        'Cukup' => 0.75,
        'Baik' => 1,
        'Sangat Baik' => 1.5,
    ];
    public array $nilaiJenjang = [
        'terampil' => 5,
        'mahir' => 12.5,
        'ahli pertama' => 12.5,
        'penyelia' => 25,
        'ahli muda' => 25,
        'ahli madya' => 37.5,
        'ahli utama' => 50,
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

    public function mount()
    {
        $this->inputs = session()->get('selectedProfiles', []);
        $this->jenis = session()->get('jenis');
        if ($this->jenis == 'Pengangkatan Kembali') {
            $this->jenis_angkat_kembali = session()->get('jenis_angkat_kembali');
        }
    }

    public function setEdit($nip)
    {
        $this->dispatch('open-edit-modal');

        $profiles = array_column($this->inputs, null, 'nip');
        $profile = $profiles[$nip];

        $this->selectedNip = $nip;
        $this->predikat = $profile['predikat'];
        $this->angka_kredit = $profile['angka_kredit'];
        $this->angka_kredit_awal = $this->nilaiJenjang[$profile['jenjang']];

        if ($this->jenis == 'Periodik') {
            $this->mulai_periode = $profile['mulai']->format('Y-m');
            $this->akhir_periode = $profile['akhir']->format('Y-m');
        } elseif ($this->jenis == 'Pengangkatan Pertama') {
            $this->golongan = $profile['golongan'];
            $this->jenjang_tujuan = $profile['jenjang_tujuan'];
            $this->mulai_periode = $profile['mulai']->format('Y-m');
            $this->akhir_periode = $profile['akhir']->format('Y-m');
        } elseif ($this->jenis == 'Perpindahan Jabatan') {
            $this->mulai_periode = $profile['mulai']->format('Y-m');
            $this->akhir_periode = $profile['akhir']->format('Y-m');
        } elseif ($this->jenis_angkat_kembali == 'CLTN') {
            $this->mulai_periode = $profile['mulai']->format('Y-m');
            $this->akhir_periode = $profile['akhir']->format('Y-m');
        } elseif ($this->jenis_angkat_kembali == 'Tugas Belajar') {
            $this->lama_tb = $profile['lama_tb'];
            $this->ak_before_tb = $profile['ak_awal'];
            $this->golongan = $profile['golongan'];
        } elseif ($this->jenis_angkat_kembali == 'Struktural ke JFT') {
            $this->mulai_periode = $profile['mulai']->format('Y-m');
            $this->akhir_periode = $profile['akhir']->format('Y-m');
            $this->ak_jft = $profile['ak_awal'];
            $this->jft_sebelum = $profile['jenjang'];
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['predikat', 'mulai_periode', 'akhir_periode', 'golongan', 'jenjang_tujuan', 'lama_tb', 'ak_before_tb'])) {
            $this->componentChanged();
        }
    }

    public function componentChanged()
    {
        $profiles = array_column($this->inputs, null, 'nip');

        if ($this->jenis == 'Tahunan') {
            $this->angka_kredit = $this->angka_kredit_awal * $this->persenPredikat[$this->predikat];
        } elseif ($this->jenis == 'Periodik') {
            $mulai = Carbon::parse($this->mulai_periode);
            $akhir = Carbon::parse($this->akhir_periode);

            // Hitung selisih bulan
            $selisih_bulan = $mulai->diffInMonths($akhir) + 1;

            // dd($this->angka_kredit_awal);
            $this->angka_kredit = ($selisih_bulan/12) * $this->persenPredikat[$this->predikat] * $this->nilaiJenjang[$profiles[$this->selectedNip]['jenjang']];
        } elseif ($this->jenis == 'Pengangkatan Pertama') {
            $mulai = Carbon::parse($this->mulai_periode);
            $akhir = Carbon::parse($this->akhir_periode);

            // Hitung selisih bulan
            $selisih_bulan = $mulai->diffInMonths($akhir) + 1;

            $ak_dasar = Golongan::where('nama', $this->golongan)->value('ak_dasar'); 

            $this->angka_kredit = ($selisih_bulan/12) * $this->persenPredikat[$this->predikat] * $this->nilaiJenjang[$this->jenjang_tujuan] + $ak_dasar;
        } elseif ($this->jenis_angkat_kembali == 'Tugas Belajar') {
            $ak_pend = 0.25 * Golongan::where('nama', $this->golongan)->value('ak_minimal');

            $this->angka_kredit = $this->ak_before_tb + $ak_pend + ($this->lama_tb/12) * $this->persenPredikat[$this->predikat] * $this->nilaiJenjang[$profiles[$this->selectedNip]['jenjang']];
        } elseif ($this->jenis_angkat_kembali == 'Struktural ke JFT') {
            $mulai = Carbon::parse($this->mulai_periode);
            $akhir = Carbon::parse($this->akhir_periode);

            // Hitung selisih bulan
            $selisih_bulan = $mulai->diffInMonths($akhir) + 1;

            $this->angka_kredit = $this->ak_jft + ($selisih_bulan/12) * $this->persenPredikat[$this->predikat] * $this->nilaiJenjang[$this->jft_sebelum];
        } elseif ($this->jenis_angkat_kembali == 'CLTN') {
            $mulai = Carbon::parse($this->mulai_periode);
            $akhir = Carbon::parse($this->akhir_periode);

            // Hitung selisih bulan
            $selisih_bulan = $mulai->diffInMonths($akhir) + 1;

            // dd($this->angka_kredit_awal);
            $this->angka_kredit = ($selisih_bulan/12) * $this->persenPredikat[$this->predikat] * $this->nilaiJenjang[$profiles[$this->selectedNip]['jenjang']];
        } elseif ($this->jenis == 'Perpindahan Jabatan') {
            $mulai = Carbon::parse($this->mulai_periode);
            $akhir = Carbon::parse($this->akhir_periode);

            // Hitung selisih bulan
            $selisih_bulan = $mulai->diffInMonths($akhir) + 1;

            // dd($this->angka_kredit_awal);
            $this->angka_kredit = ($selisih_bulan/12) * $this->persenPredikat[$this->predikat] * $this->nilaiJenjang[$profiles[$this->selectedNip]['jenjang']];
        }
    }    

    public function updateAngkaKredit()
    {
        $this->validate([
            'predikat' => 'required',
            'angka_kredit' => 'required|numeric',
        ]);

        $profiles = array_column($this->inputs, null, 'nip');

        if (isset($profiles[$this->selectedNip])) {
            $profiles[$this->selectedNip]['predikat'] = $this->predikat;
            $profiles[$this->selectedNip]['angka_kredit'] = $this->angka_kredit;

            if ($this->jenis == 'Periodik') {
                $profiles[$this->selectedNip]['mulai'] = Carbon::parse($this->mulai_periode . '-01')->startOfMonth();
                $profiles[$this->selectedNip]['akhir'] = Carbon::parse($this->akhir_periode . '-01')->endOfMonth();
            } elseif ($this->jenis == 'Pengangkatan Pertama') {
                $profiles[$this->selectedNip]['golongan'] = $this->golongan;
                $profiles[$this->selectedNip]['jenjang_tujuan'] = $this->jenjang_tujuan;    
            } elseif ($this->jenis == 'Perpindahan Jabatan') {
                
            } elseif ($this->jenis_angkat_kembali == 'Struktural ke JFT') {
                $profiles[$this->selectedNip]['mulai'] = Carbon::parse($this->mulai_periode . '-01')->startOfMonth();
                $profiles[$this->selectedNip]['akhir'] = Carbon::parse($this->akhir_periode . '-01')->endOfMonth();
                $profiles[$this->selectedNip]['ak_awal'] = $this->ak_jft;
                $profiles[$this->selectedNip]['jenjang'] =  $this->jft_sebelum;
            } elseif ($this->jenis_angkat_kembali == 'Tugas Belajar') {
                $profiles[$this->selectedNip]['lama_tb'] = $this->lama_tb;
                $profiles[$this->selectedNip]['ak_awal'] = $this->ak_before_tb;
                $profiles[$this->selectedNip]['golongan'] = $this->golongan;
            }
        }
        
        $this->inputs = array_values($profiles);

        // Simpan kembali ke session jika mau persist
        session()->put('selectedProfiles', $this->inputs);

        // Optional reset
        $this->reset('selectedNip', 'predikat', 'angka_kredit');

        // Trigger tutup modal (Alpine)
        $this->dispatch('close-modal');
    }

    public function generateWordFile($nip, $nomor)
    {
        $templatePath = storage_path('app/template_pak_bps.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        $profiles = array_column($this->inputs, null, 'nip');

        $profile = $profiles[$nip];

        if (isset($profile['jenjang'])) {
            $jenjang = $profile['jenjang'];
        } else {
            $jenjang = $profile['jenjang_tujuan'];
        }

        $data = [];

        $ak_before = AngkaKredit::where('nip', $profile['nip'])
                    ->orderBy('id', 'desc')
                    ->first();

        if ($this->jenis == 'Tahunan') {
            $persentase = '12/12';
            $periode = 'Januari - Desember ' . $profile['periode'];

            if ($ak_before) {
                $baris[0]['data_th'] = Carbon::parse($ak_before->periode_end)->year;
                $baris[0]['data_ak'] = number_format($ak_before->total_ak, 3, ',', '.');
            } else {
                $baris[0]['data_th'] = $profile['periode'] - 1;
                $baris[0]['data_ak'] = 0;
            }

            $baris[0]['data_periodik'] = '';
            $baris[0]['data_predikat'] = '';
            $baris[0]['data_persen'] = '';
            $baris[0]['data_koef'] = '';

            // selalu ada periode ke-1 untuk Tahunan dan Periodik
            $baris[1]['data_th'] = $th1 ?? $profile['periode'];
            $baris[1]['data_periodik'] = $periodik1 ?? 'Januari - Desember';
            $baris[1]['data_predikat'] = $profile['predikat'];
            $baris[1]['data_persen'] = $this->persenPredikat[$profile['predikat']] * 100;
            $baris[1]['data_koef'] = number_format($this->nilaiJenjang[$profile['jenjang']], 3, ',', '.');
            $baris[1]['data_ak'] = number_format($profile['angka_kredit'], 3, ',', '.');

            $templateProcessor->cloneRow('data_th', count($baris));

            foreach ($baris as $index => $row) {
                $i = $index + 1;
                foreach ($row as $key => $value) {
                    $templateProcessor->setValue("{$key}#{$i}", $value);
                }
            }

            $konv_lama = $profile['ak_awal'];
            $konv_baru = $profile['angka_kredit'];
            $total_konv = $konv_lama + $konv_baru;

            $total_lama = $konv_lama;
            $total_baru = $konv_baru;
            $total_ak = $total_konv;
        } elseif ($this->jenis == 'Periodik' || $this->jenis == 'Perpindahan Jabatan' || $this->jenis_angkat_kembali == 'CLTN') {
            $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;
            $persentase = $selisih_bulan . '/12';
            $periode = $profile['mulai']->translatedFormat('F') . ' ' . $profile['mulai']->year . '-' . $profile['akhir']->translatedFormat('F') . ' ' . $profile['akhir']->year;

            if ($ak_before) {
                $baris[0]['data_th'] = Carbon::parse($ak_before->periode_end)->year;
                $baris[0]['data_ak'] = number_format($ak_before->total_ak, 3, ',', '.');
                $baris[0]['data_periodik'] = Carbon::parse($ak_before->periode_start)->translatedFormat('F') . '-' . Carbon::parse($ak_before->periode_end)->translatedFormat('F');
            } else {
                $baris[0]['data_th'] = $profile['mulai']->year - 1;
                $baris[0]['data_ak'] = 0;
                $baris[0]['data_periodik'] = '';
            }

            $baris[0]['data_predikat'] = '';
            $baris[0]['data_persen'] = '';
            $baris[0]['data_koef'] = '';

            // selalu ada periode ke-1 untuk Tahunan dan Periodik
            $baris[1]['data_th'] = $profile['mulai']->year;
            $baris[1]['data_periodik'] = $profile['mulai']->translatedFormat('F') . '-' . $profile['akhir']->translatedFormat('F');
            $baris[1]['data_predikat'] = $profile['predikat'];
            $baris[1]['data_persen'] = $this->persenPredikat[$profile['predikat']] * 100;
            $baris[1]['data_koef'] = number_format($this->nilaiJenjang[$profile['jenjang']], 3, ',', '.');
            $baris[1]['data_ak'] = number_format($profile['angka_kredit'], 3, ',', '.');

            $templateProcessor->cloneRow('data_th', count($baris));

            foreach ($baris as $index => $row) {
                $i = $index + 1;
                foreach ($row as $key => $value) {
                    $templateProcessor->setValue("{$key}#{$i}", $value);
                }
            }

            $konv_lama = $profile['ak_awal'];
            $konv_baru = $profile['angka_kredit'];
            $total_konv = $konv_lama + $konv_baru;

            $total_lama = $konv_lama;
            $total_baru = $konv_baru;
            $total_ak = $total_konv;
        } elseif ($this->jenis == 'Pengangkatan Pertama') {
            $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;
            $persentase = $selisih_bulan . '/12';
            $jumlah_tahun = (int) ceil($selisih_bulan / 12);
            $periode = $profile['mulai']->translatedFormat('F') . ' ' . $profile['mulai']->year . '-' . $profile['akhir']->translatedFormat('F') . ' ' . $profile['akhir']->year;

            if ($this->jenis == 'Pengangkatan Pertama') {
                $jenjang = $profile['jenjang_tujuan'];
            } else {
                $jenjang = $profile['jenjang'];
            }

            $baris = [];

            for ($i = 0; $i < $jumlah_tahun; $i++) {
                $start = $profile['mulai']->copy()->addYears($i)->startOfYear();
                $end = $start->copy()->endOfYear();

                // Jika tahun terakhir, gunakan $profile['akhir'] sebagai akhir periode
                if ($end->greaterThan($profile['akhir'])) {
                    $end = $profile['akhir'];
                }

                if ($start->copy()->endOfYear()->greaterThan($profile['akhir'])) {
                    if ($this->jenis == 'Pengangkatan Pertama') {
                        $ak_baris = $profile["angka_kredit"] - (($jumlah_tahun-1) * ($this->persenPredikat[$profile['predikat']] * $this->nilaiJenjang[$jenjang])) - $profile["ak_dasar"];
                    } else {
                        $ak_baris = $profile["angka_kredit"] - (($jumlah_tahun-1) * ($this->persenPredikat[$profile['predikat']] * $this->nilaiJenjang[$jenjang]));
                    }
                } else {
                    $ak_baris = $this->persenPredikat[$profile['predikat']] * $this->nilaiJenjang[$jenjang];
                }

                if ($i==0 && $this->jenis == 'Pengangkatan Pertama') {
                    $ak_baris =  $ak_baris + $profile["ak_dasar"];
                }

                $baris[] = [
                    "data_th" => $start->year,
                    "data_periodik" => $start->translatedFormat('F') . ' - ' . $end->translatedFormat('F'),
                    "data_predikat" => $profile['predikat'],
                    "data_persen" =>  $this->persenPredikat[$profile['predikat']] * 100,
                    "data_koef" => number_format($this->nilaiJenjang[$jenjang], 3, ',', '.'),
                    "data_ak" => number_format($ak_baris, 3, ',', '.')
                ];
            }

            // $data = array_merge($data, $periodikData);
            $templateProcessor->cloneRow('data_th', count($baris));

            // Set value tiap baris
            foreach ($baris as $index => $row) {
                $i = $index + 1;
                foreach ($row as $key => $value) {
                    $templateProcessor->setValue("{$key}#{$i}", $value);
                }
            }

            if (isset($profile['ak_dasar'])) {
                if ($profile['ak_dasar'] == 0) {
                    $konv_baru = $profile['angka_kredit'];
                } else {
                    $ak_dasar = $profile['ak_dasar'];
                    $konv_baru = $profile['angka_kredit'] - $profile['ak_dasar'];
                }
            } elseif (isset($profile['ak_pend'])) {
                $konv_baru = $profile['angka_kredit'] - $profile['ak_pend'];

                $tb_baru = $profile['ak_pend'];
                $total_tb = $profile['ak_pend'];
            } else {
                $konv_baru = $profile['angka_kredit'];
            }

            $konv_lama = $profile['ak_awal'];
            $total_konv = $konv_lama + $konv_baru;

            $total_lama = $konv_lama;
            if ($this->jenis == 'Pengangkatan Pertama') {
                $total_baru = $konv_baru + $profile['ak_dasar'];
                $total_ak = $total_konv + $profile['ak_dasar'];
            } elseif ($this->jenis_angkat_kembali == 'Tugas Belajar') {
                $total_baru = $konv_baru + $tb_baru;
                $total_ak = $total_konv + $total_tb;
            } else {
                $total_baru = $konv_baru;
                $total_ak = $total_konv;
            }
        } elseif ($this->jenis_angkat_kembali == 'Struktural ke JFT' || $this->jenis_angkat_kembali == 'Tugas Belajar') {
            $selisih_bulan = $profile['mulai']->diffInMonths($profile['akhir']) + 1;
            $persentase = $selisih_bulan . '/12';
            $jumlah_tahun = (int) ceil($selisih_bulan / 12);
            $periode = $profile['mulai']->translatedFormat('F') . ' ' . $profile['mulai']->year . '-' . $profile['akhir']->translatedFormat('F') . ' ' . $profile['akhir']->year;
            $jenjang = $profile['jenjang'];

            $baris = [];

            if ($ak_before) {
                $i = 1;
                $baris[0]['data_th'] = Carbon::parse($ak_before->periode_end)->year;
                $baris[0]['data_ak'] = number_format($ak_before->total_ak, 3, ',', '.');
                $baris[0]['data_periodik'] = Carbon::parse($ak_before->periode_start)->translatedFormat('F') . '-' . Carbon::parse($ak_before->periode_end)->translatedFormat('F');
                $baris[0]['data_predikat'] = '';
                $baris[0]['data_persen'] = '';
                $baris[0]['data_koef'] = '';
            } else {
                $i = 0;
            }

            $first = true;
            for ($i = 0; $i < $jumlah_tahun; $i++) {
                $start = $profile['mulai']->copy()->addYears($i)->startOfYear();
                $end = $start->copy()->endOfYear();

                // Jika tahun terakhir, gunakan $profile['akhir'] sebagai akhir periode
                if ($end->greaterThan($profile['akhir'])) {
                    $end = $profile['akhir'];
                }

                if ($start->copy()->endOfYear()->greaterThan($profile['akhir'])) {
                    if ($this->jenis_angkat_kembali == 'Tugas Belajar') {
                        $ak_baris = $profile["angka_kredit"] - (($jumlah_tahun-1) * ($this->persenPredikat[$profile['predikat']] * $this->nilaiJenjang[$jenjang])) - $profile["ak_pend"];
                    } else {
                        $ak_baris = $profile["angka_kredit"] - (($jumlah_tahun-1) * ($this->persenPredikat[$profile['predikat']] * $this->nilaiJenjang[$jenjang]));
                    }
                } else {
                    $ak_baris = $this->persenPredikat[$profile['predikat']] * $this->nilaiJenjang[$jenjang];
                }

                if ($first && $this->jenis_angkat_kembali == 'Tugas Belajar') {
                    $ak_baris = $ak_baris + $profile['ak_pend'];
                }

                $baris[] = [
                    "data_th" => $start->year,
                    "data_periodik" => $start->translatedFormat('F') . ' - ' . $end->translatedFormat('F'),
                    "data_predikat" => $profile['predikat'],
                    "data_persen" =>  $this->persenPredikat[$profile['predikat']] * 100,
                    "data_koef" => number_format($this->nilaiJenjang[$jenjang], 3, ',', '.'),
                    "data_ak" => number_format($ak_baris, 3, ',', '.')
                ];

                $first = false;
            }

            // $data = array_merge($data, $periodikData);
            $templateProcessor->cloneRow('data_th', count($baris));

            // Set value tiap baris
            foreach ($baris as $index => $row) {
                $i = $index + 1;
                foreach ($row as $key => $value) {
                    $templateProcessor->setValue("{$key}#{$i}", $value);
                }
            }

            if (isset($profile['ak_pend'])) {
                $konv_baru = $profile['angka_kredit'] - $profile['ak_pend'];

                $tb_baru = $profile['ak_pend'];
                $total_tb = $profile['ak_pend'];
            } else {
                $konv_baru = $profile['angka_kredit'];
            }

            $konv_lama = $profile['ak_awal'];
            $total_konv = $konv_lama + $konv_baru;

            $total_lama = $konv_lama;
            if ($this->jenis_angkat_kembali == 'Tugas Belajar') {
                $total_baru = $konv_baru + $tb_baru;
                $total_ak = $total_konv + $total_tb;
            } else {
                $total_baru = $konv_baru;
                $total_ak = $total_konv;
            }
        }

        if ($this->jenis == 'Pengangkatan Pertama') {
            $ak = $profile['angka_kredit'] - $profile['ak_dasar'];
        } elseif ($this->jenis_angkat_kembali == 'Tugas Belajar') {
            $ak = $profile['angka_kredit'] - $profile['ak_pend'];
        } else {
            $ak = $profile['angka_kredit'];
        }

        $gol = Golongan::where('id', $profile['id_golongan'])->first();
        
        $ak_min_pangkat = $gol->ak_minimal;
        $ak_min_jenjang = $this->ak_jenjang[$gol->nama];
        $selisih_pangkat = $total_ak - $ak_min_pangkat;
        $selisih_jenjang = $total_ak - $ak_min_jenjang;

        if ($selisih_pangkat > 0) {
            $status_pangkat = 'Kelebihan ';
            $dipertimbangkan = 'Dapat';
        } else {
            $status_pangkat = 'Kekurangan ';
            $dipertimbangkan = 'Belum Dapat';
        }

        if ($selisih_jenjang > 0) {
            $status_jenjang = 'Kelebihan ';
        } else {
            $status_jenjang = 'Kekurangan ';
        }

        $gol_sekarang = $gol->nama; // Misal: 'III/b'
        $jabatan = strtolower($profile['jabatan']); // contoh: 'Statistisi Ahli Pertama'

        // Cari indeks golongan sekarang
        $keys = array_keys($this->gol_jenjang);
        $currentIndex = array_search($gol_sekarang, $keys);

        // Inisialisasi nilai default
        $next_gol = null;
        $next_jenjang = null;

        // Jika ada golongan setelahnya
        if ($currentIndex !== false && isset($keys[$currentIndex + 1])) {
            $next_gol = $keys[$currentIndex + 1];
            $jenjangOptions = $this->gol_jenjang[$next_gol];

            // Tentukan indeks jenjang
            if (str_contains($jabatan, 'ahli') || str_contains($jabatan, 'terampil')) {
                $next_jenjang = $jenjangOptions[0] ?? null;
            } else {
                $next_jenjang = $jenjangOptions[1] ?? $jenjangOptions[0] ?? null;
            }

            $next_gol_lengkap = Golongan::where('nama', $next_gol)->value('jenis') . ' / (' . $next_gol . ')';
            $rumpun = Jabatan::where('konversi', $profile['jabatan'])->value('nama_umum');
            $next_jenjang_lengkap = $rumpun . " " . ucwords($next_jenjang);
        }

        $data = [
            'nomor' => $nomor,
            'kode' => Jabatan::where('konversi', $profile['jabatan'])->value('kode'),
            'tahun' => Carbon::now()->year,
            'nama' => $profile['nama'],
            'nip' => $profile['nip'],
            'tempat_lahir' => $profile['tempat_lahir'],
            'tgl_lahir' => Carbon::parse($profile['tgl_lahir'])->format('d-m-Y'),
            'jk' => $profile['jk'] == 'PR' ? 'Perempuan' : 'Laki-laki',
            'gol' => $gol->jenis . " (" . $gol->nama . ")",
            'tmt_gol' => Carbon::parse($profile['tmt_gol'])->format('d-m-Y'),
            'jabatan' => $profile['jabatan'],
            'tmt_jab' => Carbon::parse($profile['tmt_jab'])->format('d-m-Y'),
            'satker' => Satker::where('id', $profile['id_satker'])->value('nama'),
            'predikat' => $profile['predikat'],
            'persentase' => $persentase ?? '',
            'koef' => number_format($this->nilaiJenjang[$jenjang], 3, ',', '.'),
            'ak' => number_format($ak, 3, ',', '.'),
            'periode' => $periode ?? '',
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'total_ak' => number_format($profile['ak_awal'] + $profile['angka_kredit'], 3, ',', '.'),
            'ak_dasar' => $ak_dasar ?? '',
            'konv_lama' => isset($konv_lama) ? number_format($konv_lama, 3, ',', '.') : '',
            'konv_baru' => isset($konv_baru) ? number_format($konv_baru, 3, ',', '.') : '',
            'total_konv' => isset($total_konv) ? number_format($total_konv, 3, ',', '.') : '',
            'tb_lama' => isset($tb_lama) ? number_format($tb_lama, 3, ',', '.') : '',
            'tb_baru' => isset($tb_baru) ? number_format($tb_baru, 3, ',', '.') : '',
            'total_tb' => isset($total_tb) ? number_format($total_tb, 3, ',', '.') : '',
            'total_lama' => isset($total_lama) ? number_format($total_lama, 3, ',', '.') : '',
            'total_baru' => isset($total_baru) ? number_format($total_baru, 3, ',', '.') : '',
            'total_ak' => isset($total_ak) ? number_format($total_ak, 3, ',', '.') : '',
            'ak_min_pangkat' => $ak_min_pangkat,
            'ak_min_jenjang' => $ak_min_jenjang,
            'selisih_pangkat' => $selisih_pangkat,
            'selisih_jenjang' => $selisih_jenjang,
            'status_pangkat' => $status_pangkat,
            'status_jenjang' => $status_jenjang,
            'dipertimbangkan' => $dipertimbangkan,
            'next_jenjang' => $next_jenjang_lengkap,
            'next_pangkat' => $next_gol_lengkap,
        ];

        // dd($data);

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputPath = storage_path('app/public/' . $profile['nama'] . '-PAK (' . $periode . ')' . '.docx');
        $templateProcessor->saveAs($outputPath);

        return $outputPath;
    }

    public function export($nip)
    {
        $tracking = PakNomorTracking::firstOrCreate([], ['last_number' => 0]);
        $nomor = $tracking->last_number + 1;
        $tracking->last_number = $nomor;
        $tracking->save();

        $filePath = $this->generateWordFile($nip, $nomor);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function finalisasi()
    {
        foreach ($this->inputs as $profile) {
            // dd($profile);
            $secondLatest = Profile::where('nip', $profile['nip'])
                ->orderBy('created_at', 'desc')
                ->skip(1)
                ->take(1)
                ->first()
                ?? Profile::where('nip', $profile['nip'])
                    ->orderBy('created_at', 'desc')
                    ->first();

            $golongan = Golongan::where('id', $profile['id_golongan'])->value('nama');
            
            if ($secondLatest->golongan->nama != $golongan) {
                if ($golongan == 'III/a' || $golongan == 'III/c' || $golongan == 'IV/a' || $golongan == 'IV/d' || $golongan == 'IV/e' ) {
                    $ak_total = $profile['angka_kredit'];
                }
            } else {
                $ak_before = AngkaKredit::where('nip', $profile['nip'])
                    ->orderBy('id', 'desc')
                    ->value('total_ak') ?? 0;        

                $ak_total = $ak_before + $profile['angka_kredit'];
            }

            if ($this->jenis == 'Tahunan') {
                AngkaKredit::create([
                    'id_pegawai' => $profile['id'],
                    'nip' => $profile['nip'],
                    'jenis' => $this->jenis,
                    'status' => 2,
                    'nilai' => $profile['angka_kredit'],
                    'total_ak' => $ak_total,
                    'periode_start' => $profile['periode'] . '-01-01',
                    'periode_end' => $profile['periode'] . '-12-31',
                ]);
            } elseif ($this->jenis == 'Periodik'  || $this->jenis == 'Perpindahan Jabatan' || $this->jenis == 'Pengangkatan Kembali') {
                AngkaKredit::create([
                    'id_pegawai' => $profile['id'],
                    'nip' => $profile['nip'],
                    'jenis' => $this->jenis,
                    'status' => 2,
                    'nilai' => $profile['angka_kredit'],
                    'total_ak' => $ak_total,
                    'periode_start' => $profile['mulai']->startOfMonth(),
                    'periode_end' => $profile['akhir']->endOfMonth(),
                ]);
            } elseif ($this->jenis == 'Pengangkatan Pertama') {
                AngkaKredit::create([
                    'id_pegawai' => $profile['id'],
                    'nip' => $profile['nip'],
                    'jenis' => $this->jenis,
                    'status' => 2,
                    'nilai' => $profile['angka_kredit'],
                    'total_ak' => $ak_total,
                    'periode_start' => $profile['mulai'],
                    'periode_end' => $profile['akhir'],
                ]);
            }
        }

        $this->dispatch('showFlashMessage', 'Angka Kredit Berhasil Difinalisasi!', 'success');
    }

    public function exportAll()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(420);

        $zipFileName = 'PAK_' . now()->format('Ymd_His') . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);
        $tempDir = storage_path('app/public/temp_exports');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tracking = PakNomorTracking::firstOrCreate([], ['last_number' => 0]);
        $nomor = $tracking->last_number;

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($this->inputs as $input) {
                $nip = $input['nip'];
                $nomor++;
                $docxPath = $this->generateWordFile($nip, $nomor);
                $fileNameInZip = basename($docxPath); // pak_1234567890.docx
                $zip->addFile($docxPath, $fileNameInZip);
            }

            $zip->close();

            $tracking->last_number = $nomor;
            $tracking->save();

            // Hapus semua file Word setelah dimasukkan ke ZIP
            foreach (glob($tempDir . '/*.docx') as $tempFile) {
                unlink($tempFile);
            }

            // Hapus folder jika kosong
            @rmdir($tempDir);

            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Gagal membuat file ZIP.'], 500);
        }
    }

    public function render()
    {
        // Konversi array ke Collection dan pagination manual
        $items = collect(session()->get('selectedProfiles', []));
        $perPage = 1000;
        $currentPage = $this->page ?? 1;
        $paginated = $items->forPage($currentPage, $perPage);

        return view('livewire.angka-kredit.buat.hasil-p-a-k', [
            'profiles' => new \Illuminate\Pagination\LengthAwarePaginator(
                $paginated,
                $items->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            ),
        ])->extends('layouts.app');
    }
}
