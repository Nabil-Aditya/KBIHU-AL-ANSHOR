<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infografis;
use App\Models\FileInfografis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InfografisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $infografis = Infografis::withCount('files')
            ->latest()
            ->paginate(10);
        
        return view('admin.kelola-infografis', compact('infografis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'files.*' => 'required|image|mimes:jpeg,png,jpg|max:20480' // 5MB max per file
        ]);

        // Create infografis
        $infografis = Infografis::create([
            'judul' => $validated['judul'],
            'slug' => Str::slug($validated['judul']),
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status' => $validated['status']
        ]);

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            $urutan = 1;
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $urutan . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('infografis', $fileName, 'public');

                FileInfografis::create([
                    'infografis_id' => $infografis->id,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'urutan' => $urutan
                ]);

                $urutan++;
            }
        }

        return redirect()->route('admin.infografis.index')
            ->with('success', 'Infografis berhasil ditambahkan dengan ' . ($urutan - 1) . ' file!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $infografis = Infografis::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'files.*' => 'nullable|image|mimes:jpeg,png,jpg|max:20480'
        ]);

        // Update infografis
        $infografis->update([
            'judul' => $validated['judul'],
            'slug' => Str::slug($validated['judul']),
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status' => $validated['status']
        ]);

        // Handle new file uploads if provided
        if ($request->hasFile('files')) {
            $lastUrutan = $infografis->files()->max('urutan') ?? 0;
            
            foreach ($request->file('files') as $file) {
                $lastUrutan++;
                $fileName = time() . '_' . $lastUrutan . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('infografis', $fileName, 'public');

                FileInfografis::create([
                    'infografis_id' => $infografis->id,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'urutan' => $lastUrutan
                ]);
            }
        }

        return redirect()->route('admin.infografis.index')
            ->with('success', 'Infografis berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $infografis = Infografis::findOrFail($id);

            // Delete all files from storage
            foreach ($infografis->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }

            // Delete infografis (cascade will delete file records)
            $infografis->delete();

            return redirect()->route('admin.infografis.index')
                ->with('success', 'Infografis beserta semua file berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.infografis.index')
                ->with('error', 'Gagal menghapus infografis: ' . $e->getMessage());
        }
    }

    /**
     * Delete single file from infografis
     */
    public function deleteFile($fileId)
    {
        try {
            $file = FileInfografis::findOrFail($fileId);
            
            // Delete from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file: ' . $e->getMessage()
            ], 500);
        }
    }
}