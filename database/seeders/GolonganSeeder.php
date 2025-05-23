<?php

namespace Database\Seeders;

use App\Models\Golongan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/golongan.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Golongan::create([
                    "nama" => $data['0'],
                    "ak_minimal" => $data['1'] ?: null,
                    "ak_dasar" => $data['2'] ?: 0
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
