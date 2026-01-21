<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Foto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'kategori',
        'foto',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the full URL for the foto.
     *
     * @return string
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }
        
        // Return default image if foto doesn't exist
    return null;
    }

    /**
     * Get formatted date for display.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Get formatted date time for display.
     *
     * @return string
     */
    public function getFormattedDateTimeAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    /**
     * Scope a query to only include published fotos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include draft fotos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to filter by kategori.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kategori
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Get the file size in human readable format.
     *
     * @return string|null
     */
    public function getFileSizeAttribute()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            $bytes = Storage::disk('public')->size($this->foto);
            
            if ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            }
            
            return $bytes . ' bytes';
        }
        
        return null;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Delete foto file when model is deleted
        static::deleting(function ($foto) {
            if ($foto->foto && Storage::disk('public')->exists($foto->foto)) {
                Storage::disk('public')->delete($foto->foto);
            }
        });
    }
}