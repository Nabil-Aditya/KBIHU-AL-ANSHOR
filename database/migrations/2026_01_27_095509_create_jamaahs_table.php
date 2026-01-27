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
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            
            // Data Pribadi
            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique()->nullable();
            $table->string('no_paspor')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_telepon');
            $table->string('email')->nullable();
            
            // Data KBIHU
            $table->enum('jenis_ibadah', ['haji', 'umrah', 'haji_khusus'])->nullable();
            $table->enum('gelombang', ['1', '2'])->nullable(); // untuk haji
            $table->string('paket')->nullable(); // nama paket
            $table->year('tahun_berangkat')->nullable();
            $table->enum('status_pendaftaran', ['calon', 'terdaftar', 'berkas_ok', 'berangkat', 'selesai', 'batal'])->default('calon');
            $table->enum('status_pembayaran', ['belum_bayar', 'dp', 'cicil', 'lunas'])->default('belum_bayar');
            
            // Data Keuangan
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->decimal('uang_dp', 15, 2)->default(0);
            $table->decimal('terbayar', 15, 2)->default(0);
            
            // Status
            $table->boolean('is_mahram')->default(false);
            $table->string('mahram_dengan')->nullable(); // jika wanita, siapa mahramnya
            $table->string('kelompok')->nullable(); // nama kelompok
            $table->string('pembimbing')->nullable(); // nama pembimbing
            
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};
