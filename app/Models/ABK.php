<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ABK extends Model
{
    use HasFactory;

    protected $table = "abk";
    protected $guarded = [];

    // // Relasi ke Jabatan (Satu ABK punya satu Jabatan)
    // public function jabatan()
    // {
    //     return $this->belongsTo(Jabatan::class, 'id_jabatan');
    // }

    // Relasi ke Satker (Satu ABK punya satu Satker)
    public function satker()
    {
        return $this->belongsTo(Satker::class, 'id_satker');
    }

    // Fungsi untuk menghitung jumlah pegawai
    public function getJumlahPegawai()
    {
        $jumlah_pegawai = ABK::where('id_jabatan', $this->id_jabatan)
                            ->where('id_satker', $this->id_satker)
                            ->count();

        return $jumlah_pegawai;
    }
}
