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
        Schema::create('usul_mutasi', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->unsignedBigInteger('satker_tujuan');
            $table->integer('jenis');
            $table->string('alasan');
            $table->integer('status')->default(1);
            $table->foreign('nip')->references('nip')->on('profile');
            $table->foreign('satker_tujuan')->references('id')->on('satker');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usul_mutasi');
    }
};
