<?php

namespace App\Exports;

use App\Models\ABK;
use App\Models\Jabatan;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DetailABKExport implements FromCollection, WithHeadings, WithMapping
{
    protected $selectedIds;

    public function __construct($selectedIds)
    {
        $this->selectedIds = $selectedIds;
    }

    public function collection()
    {
        return ABK::whereIn('id', $this->selectedIds)
            ->with(['satker']) // Hanya relasi satker yang digunakan
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Satker',
            'Nama Satker',
            'Nama Jabatan',
            'Formasi',
            'Eksisting',
            'Selisih'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id_satker,
            $row->satker->nama ?? '-',
            $row->jabatan ?? '-', // Ambil langsung dari ABK
            $row->formasi,
            $this->getEksisting($row->jabatan, $row->id_satker),
            $this->hitungSelisih($row->id_satker, $row->jabatan),
        ];
    }

    private function getEksisting($jabatan, $id_satker)
    {
        // Mencari daftar konversi yang sesuai dengan jabatan di ABK
        $jabatanKonversiList = Jabatan::where('konversi', $jabatan)
            ->pluck('konversi');

        return Profile::whereIn('jabatan', $jabatanKonversiList)
            ->where('id_satker', $id_satker)
            ->count();
    }

    private function hitungSelisih($id_satker, $jabatan)
    {
        // Mencari semua jabatan yang memiliki konversi sesuai dengan ABK
        $jabatanKonversiList = Jabatan::where('konversi', $jabatan)
            ->pluck('konversi');

        $jumlahPegawai = Profile::whereIn('jabatan', $jabatanKonversiList)
            ->where('id_satker', $id_satker)
            ->count();

        $jumlahFormasi = ABK::whereIn('jabatan', $jabatanKonversiList)
            ->where('id_satker', $id_satker)
            ->sum('formasi');

        if ($jumlahFormasi == 0) {
            return "-";
        }

        return $jumlahPegawai - $jumlahFormasi;
    }
}