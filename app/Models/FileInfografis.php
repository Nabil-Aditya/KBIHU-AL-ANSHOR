<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileInfografis extends Model
{
    use HasFactory;

    protected $table = 'file_infografis';

    protected $fillable = [
        'infografis_id',
        'file_path',
        'file_name',
        'urutan'
    ];

    /**
     * Relasi ke Infografis
     */
    public function infografis()
    {
        return $this->belongsTo(Infografis::class, 'infografis_id');
    }

    /**
     * Get full file URL
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}