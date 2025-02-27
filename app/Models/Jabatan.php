<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = "jabatan";
    protected $guarded = [];

    // public function abk()
    // {
    //     return $this->hasMany(ABK::class, 'id_jabatan');
    // }
}
