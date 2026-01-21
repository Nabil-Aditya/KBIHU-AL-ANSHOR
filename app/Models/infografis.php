<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infografis extends Model
{
    use HasFactory;

    protected $table = 'infografis';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relasi ke FileInfografis
     */
    public function files()
    {
        return $this->hasMany(FileInfografis::class, 'infografis_id')->orderBy('urutan');
    }

    /**
     * Get thumbnail (first file)
     */
    public function getThumbnailAttribute()
    {
        return $this->files()->first()?->file_path;
    }

    /**
     * Get total files count
     */
    public function getTotalFilesAttribute()
    {
        return $this->files()->count();
    }
}