<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'judul',
        'kategori',
        'video_url',
        'video_type',
        'thumbnail',
        'status',
    ];

    /**
     * Scope a query to only include published videos.
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
        return $this->created_at->format('d F Y');
    }

    /**
     * Get YouTube video ID from URL
     */
    public function getYoutubeIdAttribute()
    {
        if ($this->video_type !== 'youtube') {
            return null;
        }

        // Extract YouTube ID from various URL formats
        // Format: https://www.youtube.com/watch?v=VIDEO_ID
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $this->video_url, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        // Format: https://youtu.be/VIDEO_ID
        preg_match('/youtu\\.be\\/([^\\?\\&]+)/', $this->video_url, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        // Format: https://www.youtube.com/embed/VIDEO_ID
        preg_match('/youtube\\.com\\/embed\\/([^\\?\\&]+)/', $this->video_url, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get embed URL for video
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->video_type === 'youtube' && $this->youtube_id) {
            return "https://www.youtube.com/embed/{$this->youtube_id}";
        }

        return $this->video_url;
    }

    /**
     * Get thumbnail URL
     */
  public function getThumbnailUrlAttribute()
{
    // Prioritas 1: Thumbnail yang diupload
    if ($this->thumbnail) {
        $path = public_path('storage/' . $this->thumbnail);
        if (file_exists($path)) {
            return asset('storage/' . $this->thumbnail);
        }
    }

    // Prioritas 2: YouTube thumbnail
    if ($this->video_type === 'youtube' && $this->youtube_id) {
        return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
    }

    // Prioritas 3: Return null untuk menggunakan icon di view
    return null;
}
}