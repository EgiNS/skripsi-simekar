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
        Schema::create('hasil_tes_minat_karier', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->string('jabatan_1');
            $table->integer('total_1');
            $table->string('jabatan_2');
            $table->integer('total_2');
            $table->string('jabatan_3');
            $table->integer('total_3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_tes_minat_karier');
    }
};
