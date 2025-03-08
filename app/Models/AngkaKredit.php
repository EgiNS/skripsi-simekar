<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngkaKredit extends Model
{
    use HasFactory;

    protected $table = "angka_kredit";
    protected $guarded = [];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'nip', 'nip');
    }
}
