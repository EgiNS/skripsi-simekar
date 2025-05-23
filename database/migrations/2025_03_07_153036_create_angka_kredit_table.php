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
        Schema::create('angka_kredit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pegawai');
            $table->string('nip');
            $table->string('jenis');
            $table->date('periode_start')->nullable();
            $table->date('periode_end')->nullable();
            $table->integer('nilai');
            $table->integer('total_ak');
            $table->string('link_pak');
            $table->integer('status')->default(1);
            $table->foreign('id_pegawai')->references('id')->on('profile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angka_kredit');
    }
};
