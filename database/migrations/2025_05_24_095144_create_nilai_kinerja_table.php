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
        Schema::create('nilai_kinerja', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->string('nama');
            $table->decimal('nilai_perilaku', 10, 3);
            $table->decimal('nilai_kinerja', 10, 3);
            $table->string('predikat');
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_kinerja');
    }
};
