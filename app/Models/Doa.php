<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doa extends Model
{
    use HasFactory;

    protected $table = 'beritas';

    protected $fillable = [
        'judul',
        'kategori',
        'doa',
        'tanggal',
        'status',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the route key for the model.
     * TIDAK DIGUNAKAN - kita akan handle manual di route
     */
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    /**
     * Scope a query to only include published berita.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to filter by kategori.
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->tanggal->format('d F Y');
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/' . $this->gambar);
        }
        return asset('assets/img/default-news.jpg');
    }
}