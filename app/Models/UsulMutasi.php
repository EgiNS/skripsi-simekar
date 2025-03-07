<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulMutasi extends Model
{
    use HasFactory;

    protected $table = "usul_mutasi";
    protected $guarded = [];

    public function satker()
    {
        return $this->belongsTo(Satker::class, 'satker_tujuan');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'nip', 'nip');
    }
}
