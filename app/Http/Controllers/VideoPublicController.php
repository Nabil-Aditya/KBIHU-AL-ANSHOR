<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoPublicController extends Controller
{
    /**
     * Display homepage with 4 latest videos
     */
    public function home()
    {
        // Get 4 latest published videos
        $videos = Video::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        return view('index', compact('videos'));
    }

    /**
     * Display all videos page with pagination and filter
     */
    public function index(Request $request)
    {
        $query = Video::where('status', 'published');

        // Filter by category if provided
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        // Get videos with pagination
        $videos = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Get all categories for filter dropdown
        $kategoris = Video::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');
        
        return view('video', compact('videos', 'kategoris'));
    }

    /**
     * Display single video detail
     */
    public function show($id)
    {
        $video = Video::where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Get related videos (same category, exclude current)
        $relatedVideos = Video::where('status', 'published')
            ->where('kategori', $video->kategori)
            ->where('id', '!=', $video->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        // Get latest videos for sidebar
        $latestVideos = Video::where('status', 'published')
            ->where('id', '!=', $video->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('detail-video', compact('video', 'relatedVideos', 'latestVideos'));
    }

    /**
     * Get videos by category
     */
    public function category($kategori)
    {
        $videos = Video::where('status', 'published')
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $kategoris = Video::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');
        
        return view('video', compact('videos', 'kategoris', 'kategori'));
    }

    /**
     * API endpoint for getting video embed URL
     */
    public function getEmbedUrl($id)
    {
        $video = Video::where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();
        
        return response()->json([
            'embed_url' => $video->embed_url,
            'video_type' => $video->video_type,
            'video_url' => $video->video_url,
            'judul' => $video->judul
        ]);
    }
}