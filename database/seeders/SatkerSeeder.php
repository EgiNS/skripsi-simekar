<?php

namespace Database\Seeders;

use App\Models\Satker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Satker::truncate();
        $csvFile = fopen(base_path("database/data/satker.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Satker::create([
                    "id" => $data['0'],
                    "nama" => $data['1'],
                    "wilayah" => $data['2'],
                    "zona" => $data['3'] ?: null,
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
