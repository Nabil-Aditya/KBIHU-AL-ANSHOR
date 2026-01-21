<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doa = Doa::latest()->paginate(10);
        return view('admin.kelola-doa', compact('doa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('admin.berita.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,pengumuman,artikel',
            'excerpt' => 'nullable|string|max:200',
            'konten' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal' => 'required|date',
            'penulis' => 'required|string|max:255',
            'status' => 'required|in:draft,published'
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . Str::slug($request->judul) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('berita', $imageName, 'public');
            $validated['gambar'] = $imagePath;
        }

        // Generate slug
        $validated['slug'] = Str::slug($request->judul);

        Berita::create($validated);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Display the specified resource (for AJAX modal)
     * Accept ID instead of slug for admin
     */
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Return JSON with formatted date for JavaScript
        return response()->json([
            'id' => $berita->id,
            'judul' => $berita->judul,
            'slug' => $berita->slug,
            'kategori' => $berita->kategori,
            'excerpt' => $berita->excerpt,
            'konten' => $berita->konten,
            'gambar' => $berita->gambar,
            'penulis' => $berita->penulis,
            'tanggal' => $berita->tanggal->format('Y-m-d'), // Format untuk input date
            'status' => $berita->status,
            'created_at' => $berita->created_at,
            'updated_at' => $berita->updated_at
        ]);
    }

    /**
     * Show the form for editing the specified resource (for AJAX modal)
     * Accept ID instead of slug for admin
     */
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Return JSON with formatted date for JavaScript
        return response()->json([
            'id' => $berita->id,
            'judul' => $berita->judul,
            'slug' => $berita->slug,
            'kategori' => $berita->kategori,
            'excerpt' => $berita->excerpt,
            'konten' => $berita->konten,
            'gambar' => $berita->gambar,
            'penulis' => $berita->penulis,
            'tanggal' => $berita->tanggal->format('Y-m-d'), // Format untuk input date
            'status' => $berita->status
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Accept ID instead of slug for admin
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,pengumuman,artikel',
            'excerpt' => 'nullable|string|max:200',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
            'tanggal' => 'required|date',
            'penulis' => 'required|string|max:255',
            'status' => 'required|in:draft,published'
        ]);

        // Handle image upload if new image provided
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }

            $image = $request->file('gambar');
            $imageName = time() . '_' . Str::slug($request->judul) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('berita', $imageName, 'public');
            $validated['gambar'] = $imagePath;
        }

        // Update slug if title changed
        if ($berita->judul !== $request->judul) {
            $validated['slug'] = Str::slug($request->judul);
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     * Accept ID instead of slug for admin
     */
    public function destroy($id)
    {
        try {
            $berita = Berita::findOrFail($id);
            
            // Delete image from storage
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }

            $berita->delete();

            return redirect()->route('admin.berita.index')
                ->with('success', 'Berita berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.berita.index')
                ->with('error', 'Gagal menghapus berita: ' . $e->getMessage());
        }
    }

    /**
     * Search berita (optional - jika mau pakai route terpisah)
     */
    public function search(Request $request)
    {
        $query = Berita::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $beritas = $query->latest()->paginate(10);

        return view('admin.kelola-berita', compact('beritas'));
    }
}