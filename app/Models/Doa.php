<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doa extends Model
{
    use HasFactory;

    protected $table = 'doa'; // Sesuaikan dengan nama tabel

    protected $fillable = [
        'judul',
        'kategori',
        'doa', // Path file PDF
        'status',
    ];

    // Accessor untuk mendapatkan URL file
    public function getDoaUrlAttribute()
    {
        return $this->doa ? asset('storage/' . $this->doa) : null;
    }

    // Scope untuk filter status
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope untuk filter draft
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Scope untuk filter kategori
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}