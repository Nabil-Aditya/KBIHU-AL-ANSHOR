<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;

class FotoPublicController extends Controller
{
    /**
     * Display all photos page with pagination and filter
     */
    public function index(Request $request)
    {
        $query = Foto::where('status', 'published');

        // Filter by category if provided
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // LIFO - Latest photos first with pagination
        $fotos = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Get all categories for filter dropdown
        $kategoris = Foto::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');
        
        return view('foto', compact('fotos', 'kategoris'));
    }

    /**
     * Display single photo detail
     */
    public function show($id)
    {
        $foto = Foto::where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Get related photos (same category, exclude current, LIFO)
        $relatedFotos = Foto::where('status', 'published')
            ->where('kategori', $foto->kategori)
            ->where('id', '!=', $foto->id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Get latest photos for sidebar (LIFO)
        $latestFotos = Foto::where('status', 'published')
            ->where('id', '!=', $foto->id)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        return view('detail-foto', compact('foto', 'relatedFotos', 'latestFotos'));
    }

    /**
     * Get photos by category
     */
    public function category($kategori)
    {
        $fotos = Foto::where('status', 'published')
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $kategoris = Foto::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');
        
        return view('foto', compact('fotos', 'kategoris', 'kategori'));
    }

    /**
     * API endpoint for getting foto data (for modal/lightbox)
     */
    public function getFotoData($id)
    {
        $foto = Foto::where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();
        
        return response()->json([
            'id' => $foto->id,
            'judul' => $foto->judul,
            'kategori' => $foto->kategori,
            'foto_url' => $foto->foto_url,
            'created_at' => $foto->created_at->format('d F Y')
        ]);
    }
}