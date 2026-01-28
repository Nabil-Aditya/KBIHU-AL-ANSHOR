<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaAllController extends Controller
{
    /**
     * Display all news with LIFO order (Latest In First Out)
     */
    public function index(Request $request)
    {
        $query = Berita::where('status', 'published');

        // Search functionality - mencari di judul, konten, excerpt, dan penulis
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                  ->orWhere('konten', 'like', '%' . $searchTerm . '%')
                  ->orWhere('excerpt', 'like', '%' . $searchTerm . '%')
                  ->orWhere('penulis', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kategori', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by category if provided
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // LIFO - Latest news first (berdasarkan created_at)
        $beritas = $query->orderBy('created_at', 'desc')
                         ->orderBy('id', 'desc')
                         ->paginate(12);
        
        // Get all categories for filter dropdown
        $kategoris = Berita::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori', 'asc')
            ->pluck('kategori');
        
        // Get total published news count
        $totalBerita = Berita::where('status', 'published')->count();
        
        // Get search results count if searching
        $searchResultsCount = $request->filled('search') ? $beritas->total() : null;
        
        return view('berita', compact('beritas', 'kategoris', 'totalBerita', 'searchResultsCount'));
    }

    /**
     * Display all news without pagination (for specific use case)
     */
    public function allWithoutPagination()
    {
        $beritas = Berita::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('berita', compact('beritas'));
    }

    /**
     * Get news by category (LIFO)
     */
    public function category($kategori)
    {
        $beritas = Berita::where('status', 'published')
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(12);
        
        $kategoris = Berita::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori', 'asc')
            ->pluck('kategori');
        
        $totalBerita = Berita::where('status', 'published')
            ->where('kategori', $kategori)
            ->count();
        
        return view('berita', compact('beritas', 'kategoris', 'kategori', 'totalBerita'));
    }

    /**
     * Get latest news (for API or AJAX)
     */
    public function latest($limit = 10)
    {
        $beritas = Berita::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take($limit)
            ->get();
        
        return response()->json($beritas);
    }
}