<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profile";
    protected $guarded = [];

    // public function jabatan()
    // {
    //     return $this->belongsTo(Jabatan::class, 'id_jabatan'); // Sesuaikan 'id_jabatan' dengan nama field di tabel Profile
    // }

}
