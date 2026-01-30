<?php

namespace App\Http\Controllers;

use App\Models\Doa;
use Illuminate\Http\Request;

class DoaPublicController extends Controller
{
    /**
     * Display all doa page with pagination and filter
     */
    public function index(Request $request)
    {
        $query = Doa::where('status', 'published');

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

        // LIFO - Latest doa first
        $doas = $query->orderBy('created_at', 'desc')
                      ->orderBy('id', 'desc')
                      ->paginate(12);
        
        // Get all categories for filter dropdown
        $kategoris = Doa::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori', 'asc')
            ->pluck('kategori');
        
        // Get total published doa count
        $totalDoa = Doa::where('status', 'published')->count();
        
        // Get search results count if searching
        $searchResultsCount = $request->filled('search') ? $doas->total() : null;
        
        return view('doa', compact('doas', 'kategoris', 'totalDoa', 'searchResultsCount'));
    }

    /**
     * Display PDF viewer page
     */
    public function view($id)
    {
        $doa = Doa::where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();
        
        return view('doa-viewer', compact('doa'));
    }

    /**
     * Display single doa detail by ID (for API)
     */
    public function show($id)
    {
        $doa = Doa::where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();
        
        return response()->json([
            'id' => $doa->id,
            'judul' => $doa->judul,
            'kategori' => $doa->kategori,
            'doa_url' => $doa->doa_url,
            'created_at' => $doa->created_at->format('d F Y'),
        ]);
    }

    /**
     * Get doa by category
     */
    public function category($kategori)
    {
        $doas = Doa::where('status', 'published')
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(12);
        
        $kategoris = Doa::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori', 'asc')
            ->pluck('kategori');
        
        $totalDoa = Doa::where('status', 'published')
            ->where('kategori', $kategori)
            ->count();
        
        return view('doa', compact('doas', 'kategoris', 'kategori', 'totalDoa'));
    }

    /**
     * Get latest doa (for API or AJAX)
     */
    public function latest($limit = 10)
    {
        $doas = Doa::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take($limit)
            ->get();
        
        return response()->json($doas);
    }
}