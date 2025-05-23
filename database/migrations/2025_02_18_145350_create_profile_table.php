<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_satker');
            $table->unsignedBigInteger('id_golongan');
            $table->string('nip');
            $table->string('nip_bps');
            $table->string('nama');
            $table->string('kode_org');
            $table->string('jabatan');
            $table->date('tmt_jab');
            $table->date('tmt_gol');
            $table->string('status');
            $table->string('pendidikan');
            $table->date('tgl_ijazah');
            $table->date('tmt_cpns');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('jk');
            $table->string('agama');
            $table->string('username');
            $table->integer('flag');
            $table->integer('active');
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_satker')->references('id')->on('satker');
            $table->foreign('id_golongan')->references('id')->on('golongan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile');
    }
};
