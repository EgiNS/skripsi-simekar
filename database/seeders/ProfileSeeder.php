<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Satker;
use App\Models\Jabatan;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/pegawai.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Profile::create([
                    "nip" => $data['0'],
                    "id_user" => User::where('username', $data['16'])->first()->id,
                    "id_satker" => Satker::where('wilayah', $data['4'])->first()->id,
                    // "id_jabatan" => Jabatan::where('nama_simpeg', $data['3'])->first()->id,
                    "id_golongan" => DB::table('golongan')->where('nama', $data['6'])->first()->id ?? 14,
                    "nama" => $data['1'],
                    "kode_org" => $data['2'],
                    "jabatan" => Jabatan::where('nama_simpeg', $data['3'])->first()->konversi,
                    "tmt_jab" => Carbon::createFromFormat(strpos($data['5'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['5']))->format('Y-m-d'),
                    "tmt_gol" => Carbon::createFromFormat(strpos($data['7'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['7']))->format('Y-m-d'),
                    "status" => $data['8'],
                    "pendidikan" => $data['9'],
                    "tgl_ijazah" => Carbon::createFromFormat(strpos($data['10'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['10']))->format('Y-m-d'),
                    "tmt_cpns" => Carbon::createFromFormat(strpos($data['11'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['11']))->format('Y-m-d'),
                    "tempat_lahir" => $data['12'],
                    "tgl_lahir" => Carbon::createFromFormat(strpos($data['13'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['13']))->format('Y-m-d'),
                    "jk" => $data['14'],
                    "agama" => $data['15'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
