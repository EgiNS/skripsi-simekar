<?php

namespace App\Livewire\AngkaKredit\Buat;

use COM;
use Carbon\Carbon;
use App\Models\Satker;
use App\Models\Profile;
use Livewire\Component;
use App\Models\Golongan;
use App\Models\AngkaKredit;
use Illuminate\Support\Arr;
use Livewire\WithPagination;
use PhpOffice\PhpWord\TemplateProcessor;

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
        $this->angka_kredit_awal = $profile['angka_kredit'];

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

    public function export($nip)
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
                $baris[0]['data_ak'] = $ak_before->total_ak;
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
        } else {
            $status_pangkat = 'Kekurangan ';
        }

        if ($selisih_jenjang > 0) {
            $status_jenjang = 'Kelebihan ';
        } else {
            $status_jenjang = 'Kekurangan ';
        }

        $data = [
            'nama' => $profile['nama'],
            'nip' => $profile['nip'],
            'tempat_lahir' => $profile['tempat_lahir'],
            'tgl_lahir' => Carbon::parse($profile['tgl_lahir'])->format('d-m-Y'),
            'jk' => $profile['jk'] == 'PR' ? 'Perempuan' : 'Laki-laki',
            'gol' => $gol->nama,
            'tmt_gol' => Carbon::parse($profile['tmt_gol'])->format('d-m-Y'),
            'jabatan' => $profile['jabatan'],
            'tmt_jab' => Carbon::parse($profile['tmt_jab'])->format('d-m-Y'),
            'satker' => Satker::where('id', $profile['id_satker'])->value('nama'),
            'predikat' => $profile['predikat'],
            'persentase' => $persentase ?? '',
            'koef' => number_format($this->nilaiJenjang[$jenjang], 3, ',', '.'),
            'ak' => number_format($ak, 3, ',', '.'),
            'periode' => $periode ?? '',
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
            'status_jenjang' => $status_jenjang
        ];

        // dd($data);

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputPath = storage_path('app/public/export_pak_' . $profile['nip'] . '.docx');
        $templateProcessor->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        // Konversi array ke Collection dan pagination manual
        $perPage = 10;
        $currentPage = $this->page ?? 1;
        $items = collect($this->inputs);
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
