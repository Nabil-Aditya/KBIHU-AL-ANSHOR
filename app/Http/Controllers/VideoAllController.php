<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoAllController extends Controller
{
    /**
     * Display all videos with LIFO order (Latest In First Out)
     */
    public function index(Request $request)
    {
        $query = Video::where('status', 'published');

        // Search functionality - mencari di judul dan kategori
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kategori', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by category if provided
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // LIFO - Latest videos first (berdasarkan created_at)
        $videos = $query->orderBy('created_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->paginate(12);
        
        // Get all categories for filter dropdown
        $kategoris = Video::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori', 'asc')
            ->pluck('kategori');
        
        // Get total published videos count
        $totalVideo = Video::where('status', 'published')->count();
        
        // Get search results count if searching
        $searchResultsCount = $request->filled('search') ? $videos->total() : null;
        
        return view('video', compact('videos', 'kategoris', 'totalVideo', 'searchResultsCount'));
    }

    /**
     * Display all videos without pagination (for specific use case)
     */
    public function allWithoutPagination()
    {
        $videos = Video::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('video', compact('videos'));
    }

    /**
     * Get videos by category (LIFO)
     */
    public function category($kategori)
    {
        $videos = Video::where('status', 'published')
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(12);
        
        $kategoris = Video::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori', 'asc')
            ->pluck('kategori');
        
        $totalVideo = Video::where('status', 'published')
            ->where('kategori', $kategori)
            ->count();
        
        return view('video', compact('videos', 'kategoris', 'kategori', 'totalVideo'));
    }

    /**
     * Get latest videos (for API or AJAX)
     */
    public function latest($limit = 10)
    {
        $videos = Video::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take($limit)
            ->get();
        
        return response()->json($videos);
    }

    /**
     * Get video statistics
     */
    public function statistics()
    {
        $stats = [
            'total_videos' => Video::where('status', 'published')->count(),
            'categories' => Video::where('status', 'published')
                ->select('kategori')
                ->distinct()
                ->count(),
            'latest_video' => Video::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->first(),
        ];
        
        return response()->json($stats);
    }
}