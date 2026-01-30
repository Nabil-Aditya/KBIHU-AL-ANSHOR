<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Http\Request;

class InfografisAllController extends Controller
{
    /**
     * Display all infografis with LIFO order (Latest In First Out)
     */
    public function index(Request $request)
    {
        $query = Infografis::with('files')
            ->where('status', 'published');

        // Search functionality - mencari di judul dan deskripsi
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        // LIFO - Latest infografis first (berdasarkan created_at)
        $infografis = $query->orderBy('created_at', 'desc')
                           ->orderBy('id', 'desc')
                           ->paginate(12);
        
        // Get total published infografis count
        $totalInfografis = Infografis::where('status', 'published')->count();
        
        // Get search results count if searching
        $searchResultsCount = $request->filled('search') ? $infografis->total() : null;
        
        return view('infografis', compact('infografis', 'totalInfografis', 'searchResultsCount'));
    }

    /**
     * Display all infografis without pagination (for specific use case)
     */
    public function allWithoutPagination()
    {
        $infografis = Infografis::with('files')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('infografis', compact('infografis'));
    }

    /**
     * Get latest infografis (for API or AJAX)
     */
    public function latest($limit = 10)
    {
        $infografis = Infografis::with('files')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take($limit)
            ->get();
        
        return response()->json($infografis);
    }
}