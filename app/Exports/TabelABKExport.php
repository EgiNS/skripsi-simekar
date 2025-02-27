<?php

namespace App\Exports;

use App\Models\ABK;
use App\Models\Satker;
use App\Models\Jabatan;
use App\Models\Profile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TabelABKExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $namaUmumList;

    public function __construct($data, $namaUmumList)
    {
        $this->data = $data;
        $this->namaUmumList = $namaUmumList;
    }

    public function collection()
    {
        return new Collection(array_map(function ($row) {
            $formattedRow = [
                'ID' => $row['id'] ?? null,
                'Nama Satker' => $row['nama'] ?? null,
            ];

            // Tambahkan semua kolom `nama_umum` beserta nilainya
            foreach ($this->namaUmumList as $namaUmum) {
                $formattedRow[$namaUmum] = $this->hitungSelisih($row['id'], $namaUmum);
            }

            return $formattedRow;
        }, $this->data));
    }

    public function headings(): array
    {
        return array_merge(
            ['ID', 'Nama Satker'],
            $this->namaUmumList
        );
    }

    public function hitungSelisih($id_satker, $nama_umum)
    {
        // Cari semua jabatan dengan nama_umum yang sesuai
        $jabatanKonversiList = Jabatan::where('nama_umum', $nama_umum)
            ->pluck('konversi'); // Ambil daftar konversi sebagai array

        // Hitung jumlah pegawai berdasarkan konversi jabatan dan id_satker
        $jumlahPegawai = Profile::whereIn('jabatan', $jabatanKonversiList)
            ->where('id_satker', $id_satker)
            ->count();

        // Hitung jumlah formasi berdasarkan konversi jabatan dan id_satker
        $jumlahFormasi = ABK::whereIn('jabatan', $jabatanKonversiList)
            ->where('id_satker', $id_satker)
            ->sum('formasi');

        // Jika tidak ada formasi, tampilkan "-"
        if ($jumlahFormasi == 0) {
            return "-";
        }

        // Jika tidak ada pegawai, tampilkan selisih sebagai -jumlahFormasi
        if ($jumlahPegawai == 0) {
            return "-$jumlahFormasi";
        }

        // Hitung selisih pegawai - formasi
        $selisih = $jumlahPegawai - $jumlahFormasi;

        return $selisih == 0 
            ? "0" 
            : ($selisih > 0 
                ? "+$selisih" 
                : "$selisih");
    }
}