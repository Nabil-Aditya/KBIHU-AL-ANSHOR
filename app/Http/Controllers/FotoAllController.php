<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;

class FotoAllController extends Controller
{
    /**
     * Display all photos with LIFO order (Latest In First Out)
     */
    public function index(Request $request)
    {
        $query = Foto::where('status', 'published');

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

        // LIFO - Latest photos first (berdasarkan created_at)
        $fotos = $query->orderBy('created_at', 'desc')
                       ->orderBy('id', 'desc')
                       ->paginate(12);
        
        // Get all categories for filter dropdown
        $kategoris = Foto::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori', 'asc')
            ->pluck('kategori');
        
        // Get total published photos count
        $totalFoto = Foto::where('status', 'published')->count();
        
        // Get search results count if searching
        $searchResultsCount = $request->filled('search') ? $fotos->total() : null;
        
        return view('galeri', compact('fotos', 'kategoris', 'totalFoto', 'searchResultsCount'));
    }

    /**
     * Display all photos without pagination (for specific use case)
     */
    public function allWithoutPagination()
    {
        $fotos = Foto::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('galeri', compact('fotos'));
    }

    /**
     * Get photos by category (LIFO)
     */
    public function category($kategori)
    {
        $fotos = Foto::where('status', 'published')
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(12);
        
        $kategoris = Foto::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori', 'asc')
            ->pluck('kategori');
        
        $totalFoto = Foto::where('status', 'published')
            ->where('kategori', $kategori)
            ->count();
        
        return view('galeri', compact('fotos', 'kategoris', 'kategori', 'totalFoto'));
    }

    /**
     * Get latest photos (for API or AJAX)
     */
    public function latest($limit = 10)
    {
        $fotos = Foto::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take($limit)
            ->get();
        
        return response()->json($fotos);
    }
}