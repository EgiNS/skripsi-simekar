<?php

namespace App\Livewire\AngkaKredit\Buat;

use COM;
use Carbon\Carbon;
use App\Models\Satker;
use App\Models\Profile;
use Livewire\Component;
use App\Models\Golongan;
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
        $profiles = array_column($this->inputs, null, 'nip');

        $profile = $profiles[$nip];

        if ($this->jenis == 'Tahunan') {
            $persentase = '12/12';
            $periode = 'Januari - Desember ' . $profile['periode'];
        }

        // $secondLatest = Profile::where('nip', $this->nip)
        // ->orderBy('created_at', 'desc')
        // ->skip(1)
        // ->take(1)
        // ->first()
        // ?? Profile::where('nip', $this->nip)
        //     ->orderBy('created_at', 'desc')
        //     ->first();    

        // Data yang akan di-inject ke template Word
        $data = [
            'nama' => $profile['nama'],
            'nip' => $profile['nip'],
            'tempat_lahir' => $profile['tempat_lahir'],
            'tgl_lahir' => Carbon::parse($profile['tgl_lahir'])->format('d-m-Y'),
            'jk' => $profile['jk'] == 'PR' ? 'Perempuan' : 'Laki-laki',
            'gol' => Golongan::where('id', $profile['id_golongan'])->value('nama'),
            'tmt_gol' => Carbon::parse($profile['tmt_gol'])->format('d-m-Y'),
            'jabatan' => $profile['jabatan'],
            'tmt_jab' => Carbon::parse($profile['tmt_jab'])->format('d-m-Y'),
            'satker' => Satker::where('id', $profile['id_satker'])->value('nama'),
            'predikat' => $profile['predikat'],
            'persentase' => $persentase,
            'koef' => $this->nilaiJenjang[$profile['jenjang']],
            'ak' => $profile['angka_kredit'],
            'periode' => $periode,
        ];

        // dd($data);

        $templatePath = storage_path('app/template_pak_bps.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

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
