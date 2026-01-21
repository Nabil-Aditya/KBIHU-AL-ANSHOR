<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fotos = Foto::latest()->paginate(12);
        return view('admin.kelola-foto', compact('fotos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,fasilitas,dokumentasi,event',
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
            'status' => 'required|in:draft,published'
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('fotos', $fotoName, 'public');
            $validated['foto'] = $fotoPath;
        }

        Foto::create($validated);

        return redirect()->route('admin.foto.index')
            ->with('success', 'Foto berhasil ditambahkan!');
    }

    /**
     * Display the specified resource (for AJAX modal)
     */
    public function show($id)
    {
        $foto = Foto::findOrFail($id);
        
        return response()->json([
            'id' => $foto->id,
            'judul' => $foto->judul,
            'kategori' => $foto->kategori,
            'foto' => $foto->foto,
            'foto_url' => $foto->foto_url,
            'status' => $foto->status,
            'created_at' => $foto->created_at->format('d M Y H:i')
        ]);
    }

    /**
     * Show the form for editing the specified resource (for AJAX modal)
     */
    public function edit($id)
    {
        $foto = Foto::findOrFail($id);
        
        return response()->json([
            'id' => $foto->id,
            'judul' => $foto->judul,
            'kategori' => $foto->kategori,
            'foto' => $foto->foto,
            'foto_url' => $foto->foto_url,
            'status' => $foto->status
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $foto = Foto::findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,fasilitas,dokumentasi,event',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
            'status' => 'required|in:draft,published'
        ]);

        // Handle foto upload if new file provided
        if ($request->hasFile('foto')) {
            // Delete old foto file
            if ($foto->foto && Storage::disk('public')->exists($foto->foto)) {
                Storage::disk('public')->delete($foto->foto);
            }

            $fotoFile = $request->file('foto');
            $fotoName = time() . '_' . $fotoFile->getClientOriginalName();
            $fotoPath = $fotoFile->storeAs('fotos', $fotoName, 'public');
            $validated['foto'] = $fotoPath;
        }

        $foto->update($validated);

        return redirect()->route('admin.foto.index')
            ->with('success', 'Foto berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $foto = Foto::findOrFail($id);
            
            // Delete foto file from storage
            if ($foto->foto && Storage::disk('public')->exists($foto->foto)) {
                Storage::disk('public')->delete($foto->foto);
            }

            $foto->delete();

            return redirect()->route('admin.foto.index')
                ->with('success', 'Foto berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.foto.index')
                ->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }
}