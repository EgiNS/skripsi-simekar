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

        // Ambil nilai terakhir dari kolom 'flag' lalu tambahkan 1
        $lastFlag = Profile::max('flag') ?? 0;
        $currentFlag = $lastFlag + 1;

        // Set semua active sebelumnya menjadi 0 (jika ada yang 1)
        Profile::where('active', 1)->update(['active' => 0]);

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Profile::create([
                    "nip_bps" => $data['0'],
                    "nip" => $data['1'],
                    "id_user" => User::where('username', $data['17'])->first()->id,
                    "id_satker" => Satker::where('wilayah', $data['5'])->first()->id,
                    // "id_jabatan" => Jabatan::where('nama_simpeg', $data['4'])->first()->id,
                    "id_golongan" => DB::table('golongan')->where('nama', $data['7'])->first()->id ?? 14,
                    "nama" => $data['2'],
                    "kode_org" => $data['3'],
                    "jabatan" => Jabatan::where('nama_simpeg', $data['4'])->first()->konversi,
                    "tmt_jab" => Carbon::createFromFormat(strpos($data['6'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['6']))->format('Y-m-d'),
                    "tmt_gol" => Carbon::createFromFormat(strpos($data['8'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['8']))->format('Y-m-d'),
                    "status" => $data['9'],
                    "pendidikan" => $data['10'],
                    "tgl_ijazah" => Carbon::createFromFormat(strpos($data['11'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['11']))->format('Y-m-d'),
                    "tmt_cpns" => Carbon::createFromFormat(strpos($data['12'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['12']))->format('Y-m-d'),
                    "tempat_lahir" => $data['13'],
                    "tgl_lahir" => Carbon::createFromFormat(strpos($data['14'], '-') !== false ? 'd-m-Y' : 'm/d/Y', trim($data['14']))->format('Y-m-d'),
                    "jk" => $data['15'],
                    "agama" => $data['16'],
                    "username" => $data['17'],
                    "flag" => $currentFlag, // Set flag sesuai batch
                    "active" => 1, // Data baru selalu active = 1
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
