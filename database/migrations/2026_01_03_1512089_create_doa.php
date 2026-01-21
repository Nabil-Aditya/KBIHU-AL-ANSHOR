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
        Schema::create('doa', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('kategori', ['kegiatan', 'pengumuman', 'artikel']);
            $table->string('doa'); // Path doa
            $table->date('tanggal');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doa');
    }
};