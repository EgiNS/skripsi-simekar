<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AngkaKreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/pegawai_prov.csv"), "r");
        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $nip     = trim($data[0]);   // Asumsikan kolom 0 = NIP
                $jabatan = strtolower($data[1]); // Kolom 1 = Jabatan

                $user = Profile::where('nip', $nip)->first();

                // Tentukan nilai berdasarkan jabatan
                if (str_contains($jabatan, 'terampil')) {
                    $nilai = rand(15, 35);
                } elseif (str_contains($jabatan, 'mahir') || str_contains($jabatan, 'pertama')) {
                    $nilai = rand(20, 90);
                } elseif (str_contains($jabatan, 'penyelia') || str_contains($jabatan, 'muda')) {
                    $nilai = rand(25, 180);
                } elseif (str_contains($jabatan, 'madya')) {
                    $nilai = rand(40, 220);
                } else {
                    $nilai = rand(10, 50);
                }

                // Simpan ke tabel angka_kredit
                DB::table('angka_kredit')->insert([
                    'id_pegawai'    => $user->id_user,
                    'nip'           => $nip,
                    'jenis'         => 'Tahunan',
                    'periode_start' => '2024-01-01',
                    'periode_end'   => '2024-12-31',
                    'nilai'         => $nilai,
                    'total_ak'      => $nilai,
                    'link_pak'      => 'https://drive.google.com',
                    'status'        => 2,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            $firstline = false;
        }

        fclose($csvFile);
    }
}
