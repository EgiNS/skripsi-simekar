<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    protected $table = "satker";
    protected $guarded = [];

    // Relasi ke ABK (Satu Satker bisa dimiliki oleh banyak ABK)
    public function abk()
    {
        return $this->hasMany(ABK::class, 'id_satker');
    }
}