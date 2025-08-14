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
        return $this->belongsTo(Profile::class, 'id_pegawai', 'id');
    }

    /**
     * Metode untuk mendapatkan entri AngkaKredit berikutnya berdasarkan NIP dan ID.
     * Digunakan untuk update berantai.
     */
    public function nextEntry()
    {
        return static::where('nip', $this->nip)
                     ->where('id', '>', $this->id)
                     ->orderBy('id', 'asc')
                     ->first();
    }

    /**
     * Metode untuk mendapatkan entri AngkaKredit sebelumnya berdasarkan NIP dan ID.
     * Digunakan untuk menghitung total_ak yang diharapkan.
     */
    public function previousEntry()
    {
        return static::where('nip', $this->nip)
                     ->where('id', '<', $this->id)
                     ->orderBy('id', 'desc')
                     ->first();
    }
}
