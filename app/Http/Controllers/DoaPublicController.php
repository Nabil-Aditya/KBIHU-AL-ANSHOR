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

        // Filter by category if provided
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // LIFO - Latest doa first
        $doas = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Get all categories for filter dropdown
        $kategoris = Doa::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');
        
        return view('doa', compact('doas', 'kategoris'));
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
            ->paginate(12);
        
        $kategoris = Doa::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');
        
        return view('doa', compact('doas', 'kategoris', 'kategori'));
    }
}