<?php

namespace Database\Seeders;

use App\Models\ABK;
use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ABKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/abk.csv"), "r");
        $firstline = true;
        $kataDihapus = ['Terampil', 'Mahir', 'Penyelia', 'Ahli Pertama', 'Ahli Muda', 'Ahli Madya', 'Ahli Utama'];

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $satkerKode = $data[0]; // Ambil kode Satker dari CSV
                $jabatanNama = $data[1]; // Ambil nama Jabatan dari CSV
                $formasi = (int) $data[2]; // Ambil Formasi dari CSV

                // Cari ID Jabatan berdasarkan konversi
                $jabatan = Jabatan::where('konversi', $jabatanNama)->first();

                // Jika Jabatan tidak ditemukan, buat data baru
                if (!$jabatan) {
                    $nama_umum = trim(str_replace($kataDihapus, '', $jabatanNama));

                    $jabatan = Jabatan::create([
                        "nama_simpeg" => $jabatanNama,
                        "konversi" => $jabatanNama,
                        "nama_umum" => $nama_umum,
                    ]);
                }

                // Masukkan data ke dalam tabel `abk`
                ABK::create([
                    'id_satker' => $satkerKode,
                    'jabatan' => $jabatanNama,
                    'formasi' => $formasi,
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
