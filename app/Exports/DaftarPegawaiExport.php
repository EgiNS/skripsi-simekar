<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DaftarPegawaiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $selectedIds;

    public function __construct($selectedIds)
    {
        $this->selectedIds = $selectedIds;
    }

    public function collection()
    {
        return Profile::whereIn('nip', $this->selectedIds)
            ->with(['satker', 'golongan']) // Relasi yang digunakan
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Kode Org',
            'Jabatan',
            'Satker',
            'TMT Jabatan',
            'Masa Kerja Jabatan',
            'Golongan Akhir',
            'TMT Golongan',
            'Status',
            'Pendidikan (SK)',
            'Tanggal Ijazah',
            'TMT CPNS',
            'Masa Kerja Keseluruhan',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama'
        ];
    }

    public function map($row): array
    {
        return [
            $row->nip,
            $row->nama,
            $row->kode_org ?? '-',
            $row->jabatan ?? '-',
            $row->satker->nama ?? '-',
            $row->tmt_jab ?? '-',
            $this->hitungMasaKerja($row->tmt_jab),
            $row->golongan->nama ?? '-',
            $row->tmt_gol ?? '-',
            $row->status ?? '-',
            $row->pendidikan ?? '-',
            $row->tgl_ijazah ?? '-',
            $row->tmt_cpns ?? '-',
            $this->hitungMasaKerja($row->tmt_cpns),
            $row->tempat_lahir ?? '-',
            $row->tgl_lahir ?? '-',
            $row->jk ?? '-',
            $row->agama ?? '-',
        ];
    }

    private function hitungMasaKerja($tmt)
    {
        if (!$tmt) return '-';

        $tmtDate = Carbon::parse($tmt);
        $now = Carbon::now();
        $diff = $tmtDate->diff($now);

        return "{$diff->y} tahun {$diff->m} bulan {$diff->d} hari";
    }
}
