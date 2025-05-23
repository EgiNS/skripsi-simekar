<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekomKarier extends Model
{
    use HasFactory;

    protected $table = "rekom_karier";
    protected $guarded = [];

    protected $casts = [
        'syarat' => 'array',
    ];    
}
