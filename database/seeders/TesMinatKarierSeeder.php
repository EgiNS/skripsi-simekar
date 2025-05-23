<?php

namespace Database\Seeders;

use App\Models\TesMinatKarier;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TesMinatKarierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatanList = [
            'Statistisi',
            'Pranata Komputer',
            'Pranata Keuangan APBN',
            'Analis SDM Aparatur',
            'Arsiparis',
            'Pranata Humas'
        ];

        $soalList = [
            "Saya senang menganalisis data dan menemukan pola yang tersembunyi.",
            "Saya tertarik mengembangkan aplikasi yang bermanfaat untuk masyarakat.",
            "Saya teliti dan suka bekerja dengan angka dalam laporan keuangan.",
            "Saya suka mengelola dokumen dan memastikan arsip tersimpan rapi.",
            "Saya menikmati bekerja dengan publik dan menyampaikan informasi penting.",
            "Saya ingin berperan dalam mengelola sumber daya manusia yang efektif.",
            "Saya tertarik pada dunia komunikasi dan penyebaran informasi resmi.",
            "Saya suka mengembangkan sistem informasi yang efisien.",
            "Saya merasa puas saat dapat membantu organisasi menjadi lebih tertib.",
            "Saya terbiasa berpikir logis dan menyelesaikan masalah teknis.",
            "Saya senang membuat laporan dan mendokumentasikan hasil pekerjaan.",
            "Saya antusias saat berbicara di depan umum atau menulis artikel.",
        ];

        for ($i = 1; $i <= 28; $i++) {
            TesMinatKarier::create([
                'soal' => $soalList[array_rand($soalList)],
                'jabatan' => $jabatanList[array_rand($jabatanList)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
