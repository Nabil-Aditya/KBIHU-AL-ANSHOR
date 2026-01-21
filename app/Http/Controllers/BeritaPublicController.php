<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaPublicController extends Controller
{
    /**
     * Display homepage with 6 latest news and 3 latest videos
     */
    public function home()
    {
        // Get 6 latest published news
        $beritas = Berita::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Get 3 latest published videos
        $videos = Video::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('index', compact('beritas', 'videos'));
    }

    /**
     * Display all news page with pagination and filter
     */
    public function index(Request $request)
    {
        $query = Berita::where('status', 'published');

        // Filter by category if provided
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        // LIFO - Latest news first
        $beritas = $query->orderBy('created_at', 'desc')->paginate(9);
        
        // Get all categories for filter dropdown
        $kategoris = Berita::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');
        
        return view('berita', compact('beritas', 'kategoris'));
    }

    /**
     * Display single news detail by slug
     */
    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Get related news (same category, exclude current, LIFO)
        $relatedNews = Berita::where('status', 'published')
            ->where('kategori', $berita->kategori)
            ->where('id', '!=', $berita->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        // Get latest news for sidebar (LIFO)
        $latestNews = Berita::where('status', 'published')
            ->where('id', '!=', $berita->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('detail-berita', compact('berita', 'relatedNews', 'latestNews'));
    }

    /**
     * Get news by category
     */
    public function category($kategori)
    {
        $beritas = Berita::where('status', 'published')
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        
        $kategoris = Berita::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');
        
        return view('berita', compact('beritas', 'kategoris', 'kategori'));
    }
}