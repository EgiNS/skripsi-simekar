<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profile";
    protected $guarded = [];

    public function satker()
    {
        return $this->belongsTo(Satker::class, 'id_satker'); // Sesuaikan 'id_jabatan' dengan nama field di tabel Profile
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_golongan'); // Sesuaikan 'id_jabatan' dengan nama field di tabel Profile
    }
}
