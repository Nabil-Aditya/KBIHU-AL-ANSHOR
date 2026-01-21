<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Video;
use App\Models\Foto;
use App\Models\Infografis;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display homepage with berita, videos, fotos, and infografis
     */
    public function index()
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
        
        // Get 6 latest published photos
        $fotos = Foto::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Get 4 latest published infografis with files relationship
        $infografis = Infografis::where('status', 'published')
            ->with(['files' => function($query) {
                $query->orderBy('urutan');
            }])
            ->withCount('files')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        return view('index', compact('beritas', 'videos', 'fotos', 'infografis'));
    }
}