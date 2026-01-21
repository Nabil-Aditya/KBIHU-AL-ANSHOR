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
        Schema::create('file_infografis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infografis_id')->constrained('infografis')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_infografis');
    }
};