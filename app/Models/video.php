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
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $this->video_url, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        // For youtu.be format
        preg_match('/youtu\\.be\\/([^\\?\\&]+)/', $this->video_url, $matches);
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
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        // Default YouTube thumbnail
        if ($this->video_type === 'youtube' && $this->youtube_id) {
            return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
        }

        return asset('assets/img/default-video.jpg');
    }
}