<?php

namespace App\Imports;

use App\Models\NilaiKinerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiKinerjaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Konversi nip ke string untuk menghindari notasi ilmiah
        $nip = (string) $row['nip'];

        // Cari data berdasarkan NIP
        $existing = NilaiKinerja::where('nip', $nip)->first();

        if ($existing) {
            // Jika ada, update
            $existing->update([
                'nama'           => $row['nama'],
                'nilai_perilaku' => $row['nilai_perilaku'],
                'nilai_kinerja'  => $row['nilai_kinerja'],
                'predikat'       => $row['predikat'],
                'tahun'          => $row['tahun'],
            ]);
            return null; // Karena update, tidak perlu return model baru
        }

        // Jika tidak ada, buat entri baru
        return new NilaiKinerja([
            'nip'            => $nip,
            'nama'           => $row['nama'],
            'nilai_perilaku' => $row['nilai_perilaku'],
            'nilai_kinerja'  => $row['nilai_kinerja'],
            'predikat'       => $row['predikat'],
            'tahun'          => $row['tahun'],
        ]);
    }
}
