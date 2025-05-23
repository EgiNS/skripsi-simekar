<?php

namespace App\Livewire\Mutasi\Kepala;

use Carbon\Carbon;
use App\Models\Profile;
use Livewire\Component;
use ZipStream\ZipStream;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RotasiKepalaExport;
use Illuminate\Support\Facades\App;

class SimulasiKepala extends Component
{
    public $step = 1;
    public $selectedData = []; // Tambahkan ini untuk menyimpan data yang dipilih
    public $allSatker = [];
    public $kandidat = '';
    public $suggestions = [];

    protected $listeners = ['ubahStep' => 'ubahStep']; // Dengarkan event 'ubahStep'

    // Method untuk mengubah step
    public function ubahStep($step, $selectedData, $allSatker)
    {
        $this->step = $step;
        $this->selectedData = $selectedData;
        $this->allSatker = $allSatker;
    }

    public function prevPage()
    {
        $this->step -= 1; // Kembali ke halaman pertama
    }

    public function updatedKandidat()
    {
        $this->suggestions = Profile::where('active',1)
            ->where('nama', 'like', '%' . $this->kandidat . '%')
            ->whereBetween('id_golongan', [8, 13])
            ->limit(5)
            ->pluck('nama')
            ->toArray();
    }

    public function selectKandidat($kandidat)
    {
        // $this->selectedData[] = Profile::where('nama', $kandidat)->pluck('nip');
        $profile = Profile::where('nama', $kandidat)->first();
        $this->selectedData[] = [
            'nama' => $profile->nama,
            'nip' => $profile->nip,
            'jabatan' => $profile->jabatan,
            'satker_asal' => $profile->satker->nama,
            'zona' => null,
            'tmt_jab' => $this->hitungMasaKerja($profile->tmt_jab, 'jab'),
            'satker_tujuan' => '-'
        ];
        $this->suggestions = [];
    }

    private function hitungMasaKerja($tmt, $jenis)
    {
        if (!$tmt) return '-';

        $tmt = Carbon::parse($tmt);
        $sekarang = Carbon::now();
        $selisih = $tmt->diff($sekarang);
        

        if ($jenis == "jab") {
            return "{$selisih->y} tahun {$selisih->m} bulan {$selisih->d} hari";
        } else {
            return "{$selisih->y} tahun {$selisih->m} bulan";
        }
    }

    public function hapusData($nip)
    {
        // Cari index data berdasarkan NIP
        $index = array_search($nip, array_column($this->selectedData, 'nip'));

        // Jika data ditemukan, hapus dari array
        if ($index !== false) {
            unset($this->selectedData[$index]);
            $this->selectedData = array_values($this->selectedData); // Reset array keys
        }
    }

    public function pageHasil()
    {
        $this->step = 3;

        $this->selectedData = collect($this->selectedData)->map(function ($item) {
            $item['satker_tujuan'] = isset($item['satker_tujuan']) && $item['satker_tujuan'] != ''
                ? \App\Models\Satker::find($item['satker_tujuan'])->nama ?? '-'
                : '-';
            return $item;
        });        
        
    }

    public function download() {
        $data = collect($this->selectedData)->map(function ($item) {
            return [
                'nip'           => $item['nip'],
                'nama'          => $item['nama'],
                'jabatan'       => $item['jabatan'],
                'satker_asal'   => $item['satker_asal'],
                'satker_tujuan' => $item['satker_tujuan']
            ];
        })->toArray();
    
        // Generate PDF
        $pdf = Pdf::loadView('livewire.mutasi.kepala.pdf', ['data' => $data])->output();

        $excel = App::make(Excel::class);

        $excelFile = $excel->raw(new RotasiKepalaExport($data), \Maatwebsite\Excel\Excel::XLSX);

        // Buat ZIP
        $zip = new ZipStream(
            outputName: 'simulasi-mutasi-' . now()->format('dmy-His') . '.zip',
        
            // enable output of HTTP headers
            sendHttpHeaders: true,
        );

        // Tambahkan PDF ke dalam ZIP
        $zip->addFile('simulasi-mutasi-' . now()->format('dmy-His') . '.pdf', $pdf);

        // Tambahkan Excel ke dalam ZIP
        $zip->addFile('simulasi-mutasi-' . now()->format('dmy-His') . '.xlsx', $excelFile);

        // Kirim ZIP ke browser
        $zip->finish();
    }

    public function render()
    {
        return view('livewire.mutasi.kepala.simulasi-kepala')->extends('layouts.app');
    }
}
