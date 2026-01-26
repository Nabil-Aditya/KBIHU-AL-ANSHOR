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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,pengumuman,artikel',
            'doa' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'status' => 'required|in:draft,published'
        ]);

        // Handle file upload
        if ($request->hasFile('doa')) {
            $file = $request->file('doa');
            $fileName = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('doa', $fileName, 'public');
            $validated['doa'] = $filePath;
        }

        Doa::create($validated);

        return redirect()->route('admin.doa.index')
            ->with('success', 'Doa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource (for AJAX modal)
     */
    public function show($id)
    {
        $doa = Doa::findOrFail($id);
        
        return response()->json([
            'id' => $doa->id,
            'judul' => $doa->judul,
            'kategori' => $doa->kategori,
            'doa' => $doa->doa,
            'status' => $doa->status,
            'created_at' => $doa->created_at->format('Y-m-d'),
        ]);
    }

    /**
     * Show the form for editing the specified resource (for AJAX modal)
     */
    public function edit($id)
    {
        $doa = Doa::findOrFail($id);
        
        return response()->json([
            'id' => $doa->id,
            'judul' => $doa->judul,
            'kategori' => $doa->kategori,
            'doa' => $doa->doa,
            'status' => $doa->status,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $doa = Doa::findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,pengumuman,artikel',
            'doa' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'required|in:draft,published'
        ]);

        // Handle file upload if new file provided
        if ($request->hasFile('doa')) {
            // Delete old file
            if ($doa->doa && Storage::disk('public')->exists($doa->doa)) {
                Storage::disk('public')->delete($doa->doa);
            }

            $file = $request->file('doa');
            $fileName = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('doa', $fileName, 'public');
            $validated['doa'] = $filePath;
        } else {
            // Keep existing file
            $validated['doa'] = $doa->doa;
        }

        $doa->update($validated);

        return redirect()->route('admin.doa.index')
            ->with('success', 'Doa berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $doa = Doa::findOrFail($id);
            
            // Delete file from storage
            if ($doa->doa && Storage::disk('public')->exists($doa->doa)) {
                Storage::disk('public')->delete($doa->doa);
            }

            $doa->delete();

            return redirect()->route('admin.doa.index')
                ->with('success', 'Doa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.doa.index')
                ->with('error', 'Gagal menghapus doa: ' . $e->getMessage());
        }
    }

    /**
     * Search doa (optional)
     */
    public function search(Request $request)
    {
        $query = Doa::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $doa = $query->latest()->paginate(10);

        return view('admin.kelola-doa', compact('doa'));
    }
}