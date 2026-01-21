<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_links', function (Blueprint $table) {
            $table->id();

            // relasi ke user (admin / operator)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // link asli
            $table->text('original_url');

            // kode short link 
            $table->string('short_code')->unique();

            // status aktif / nonaktif
            $table->boolean('is_active')->default(true);

            // jumlah klik
            $table->unsignedBigInteger('clicks')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
