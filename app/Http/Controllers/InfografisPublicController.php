<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Http\Request;

class InfografisPublicController extends Controller
{
    /**
     * Display all infografis page
     */
    public function index(Request $request)
    {
        $query = Infografis::where('status', 'published')
            ->with('files');

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // LIFO - Latest first
        $infografis = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('infografis', compact('infografis'));
    }

    /**
     * Display single infografis detail
     */
    public function show($slug)
    {
        $infografis = Infografis::where('slug', $slug)
            ->where('status', 'published')
            ->with('files')
            ->firstOrFail();
        
        // Get latest infografis for sidebar
        $latestInfografis = Infografis::where('status', 'published')
            ->where('id', '!=', $infografis->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('detail-infografis', compact('infografis', 'latestInfografis'));
    }

    /**
     * Get infografis data for AJAX/API (used in modal)
     */
    public function getInfografisData($id)
    {
        $infografis = Infografis::with('files')->findOrFail($id);
        
        return response()->json([
            'id' => $infografis->id,
            'judul' => $infografis->judul,
            'slug' => $infografis->slug,
            'deskripsi' => $infografis->deskripsi,
            'status' => $infografis->status,
            'total_files' => $infografis->files->count(),
            'files' => $infografis->files->map(function($file) {
                return [
                    'id' => $file->id,
                    'file_name' => $file->file_name,
                    'file_url' => asset('storage/' . $file->file_path),
                    'urutan' => $file->urutan
                ];
            }),
            'created_at' => $infografis->created_at->format('d F Y'),
            'updated_at' => $infografis->updated_at->format('d F Y H:i')
        ]);
    }
}