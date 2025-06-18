<?php

namespace App\Livewire\Mutasi\Pegawai;

use Carbon\Carbon;
use App\Models\ABK;
use App\Models\Satker;
use App\Models\Profile;
use Livewire\Component;
use ZipStream\ZipStream;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use App\Exports\MutasiPegawaiExport;
use App\Livewire\Mutasi\MutasiPegawai\MutasiPegawai;
use App\Models\NilaiKinerja;

class SimulasiPegawai extends Component
{
    public $inputs = []; // Menyimpan daftar input pegawai
    public $allSatker;
    public $suggestionsNama = [];
    public $detailedData = [];
    public $step = 1;

    protected $listeners = ['sendSelectedData' => 'handleSelectedData'];
    
    public function mount()
    {
        // Ambil data dari session
        $this->inputs = session()->pull('selectedData', []); // Hapus setelah diambil

        // Jika kosong, set default satu input kosong
        if (empty($this->inputs)) {
            $this->inputs[] = ['nama' => '', 'satker' => ''];
        }

        $this->allSatker = Satker::all();
    }

    public function addInput()
    {
        $this->inputs[] = ['nama' => '', 'satker' => '']; // Tambah input baru
    }

    public function removeInput($index)
    {
        unset($this->inputs[$index]);
        $this->inputs = array_values($this->inputs); // Reset array index
    }

    public function updatedInputs($value, $index)
    {
        if (strpos($index, '.nama') !== false) {
            $key = explode('.', $index)[0]; // Ambil index angka
            $this->suggestionsNama[$key] = Profile::where('active',1)
                ->where('nama', 'like', '%' . $value . '%')
                ->distinct()
                ->limit(5)
                ->pluck('nama')
                ->toArray();
        }
    }

    public function selectNama($index, $nama)
    {
        $this->inputs[$index]['nama'] = $nama;
        $this->suggestionsNama[$index] = [];
    }

    public function nextPage()
    {
        $this->detailedData = [];

        foreach ($this->inputs as $input) {
            $profile = Profile::where('nama', $input['nama'])->first();

            if ($profile) {
                $jabatan = $profile->jabatan;
                $satkerTujuan = $input['satker'];

                // Ambil semua satker yang memiliki formasi lebih besar dari eksisting
                $satkerList = ABK::where('jabatan', $jabatan)
                    ->get()
                    ->filter(function ($abk) {
                        $eksisting = $this->getEksisting($abk->jabatan, $abk->id_satker);
                        return $abk->formasi > $eksisting;
                    })
                    ->map(function ($abk) {
                        return [
                            'id' => $abk->id_satker,
                            'nama' => Satker::find($abk->id_satker)->nama ?? 'Tidak Ditemukan',
                            'formasi' => $abk->formasi,
                            'eksisting' => $this->getEksisting($abk->jabatan, $abk->id_satker),
                        ];
                    })
                    ->values();

                $satkerTerpilih = $satkerList->firstWhere('id', $satkerTujuan) ?? ['formasi' => 0, 'eksisting' => 0];

                // Simpan data lengkap
                $this->detailedData[] = [
                    'nama' => $profile->nama,
                    'nip' => $profile->nip,
                    'jabatan' => $profile->jabatan,
                    'satker_asal' => $profile->satker->nama,
                    'tmt_jab' => $this->hitungMasaKerja($profile->tmt_jab),
                    'tmt_cpns' => $this->hitungMasaKerja($profile->tmt_cpns),
                    'nilai_perilaku' => NilaiKinerja::where('nip', $profile->nip)->value('nilai_perilaku'),
                    'nilai_kinerja' => NilaiKinerja::where('nip', $profile->nip)->value('nilai_kinerja'),
                    'predikat' => NilaiKinerja::where('nip', $profile->nip)->value('predikat'),
                    'satker_tujuan' => Satker::find($satkerTujuan)->nama ?? 'Tidak Ditemukan',
                    'formasi' => $satkerTerpilih['formasi'],
                    'eksisting' => $satkerTerpilih['eksisting'],
                    'satker_eligible' => $satkerList,
                    'keputusan' => $pegawai->keputusan ?? '',
                ];
            }
        }
    
        // Pindah ke halaman detail pegawai
        $this->step = 2;
    }

    public function prevPage()
    {
        $this->step -= 1; // Kembali ke halaman pertama
    }

    public function getEksisting($jabatan, $satker)
    {
        return Profile::where(['jabatan'=>$jabatan, 'id_satker'=>$satker, 'active'=>1])
            ->count();
    }

    private function hitungMasaKerja($tmt)
    {
        if (!$tmt) return '-';

        $tmt = Carbon::parse($tmt);
        $sekarang = Carbon::now();
        $selisih = $tmt->diff($sekarang);

        return "{$selisih->y} tahun {$selisih->m} bulan {$selisih->d} hari";
    }

    public function updateSatkerTujuan($index, $satkerId)
    {
        $pegawai = &$this->detailedData[$index];

        // Cari satker yang dipilih dari daftar eligible
        $selectedSatker = collect($pegawai['satker_eligible'])->firstWhere('id', $satkerId);

        if ($selectedSatker) {
            // Update satker tujuan, formasi, eksisting, dan status
            $pegawai['satker_tujuan'] = $selectedSatker['nama'];
            $pegawai['formasi'] = $selectedSatker['formasi'];
            $pegawai['eksisting'] = $selectedSatker['eksisting'];
        }

        // Perbarui status eligibility
        $pegawai['status'] = ($pegawai['formasi'] > $pegawai['eksisting']) ? 'Eligible' : 'Tidak Eligible';

        $this->dispatch('close-modal');
    }

    public function pageHasil()
    {
        $this->step = 3;
    }

    public function download() {
        $data = collect($this->detailedData)->map(function ($item) {
            return [
                'nip'           => $item['nip'],
                'nama'          => $item['nama'],
                'jabatan'       => $item['jabatan'],
                'satker_asal'   => $item['satker_asal'],
                'satker_tujuan' => $item['satker_tujuan'],
                'keputusan'     => $item['keputusan'] ?? 'Belum Ditentukan',
            ];
        })->toArray();
    
        // Generate PDF
        $pdf = Pdf::loadView('livewire.mutasi.pegawai.pdf', ['data' => $data])->output();

        $excel = App::make(Excel::class);

        $excelFile = $excel->raw(new MutasiPegawaiExport($data), \Maatwebsite\Excel\Excel::XLSX);

        // Buat ZIP
        $zip = new ZipStream(
            outputName: 'simulasi-mutasi-' . now()->format('dmy') . '.zip',
        
            // enable output of HTTP headers
            sendHttpHeaders: true,
        );

        // Tambahkan PDF ke dalam ZIP
        $zip->addFile('simulasi-mutasi-' . now()->format('dmy') . '.pdf', $pdf);

        // Tambahkan Excel ke dalam ZIP
        $zip->addFile('simulasi-mutasi-' . now()->format('dmy') . '.xlsx', $excelFile);

        // Kirim ZIP ke browser
        $zip->finish();
    }

    public function render()
    {
        return view('livewire.mutasi.pegawai.simulasi-pegawai')->extends('layouts.app');
    }
}
