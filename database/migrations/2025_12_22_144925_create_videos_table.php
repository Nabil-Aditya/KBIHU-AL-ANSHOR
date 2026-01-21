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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('kategori', ['kegiatan', 'pengumuman', 'tutorial', 'dokumentasi']);
            $table->string('video_url'); // URL YouTube/Vimeo atau path file
            $table->enum('video_type', ['youtube', 'file'])->default('youtube'); // Tipe video
            $table->string('thumbnail')->nullable(); // Thumbnail optional
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};