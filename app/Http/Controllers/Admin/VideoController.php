<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::latest()->paginate(10);
        return view('admin.kelola-video', compact('videos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,pengumuman,tutorial,dokumentasi',
            'video_type' => 'required|in:youtube,file',
            'video_url' => 'required_if:video_type,youtube|nullable|string',
            'video_file' => 'required_if:video_type,file|nullable|file|mimes:mp4,avi,mov,wmv|max:20480', // 50MB
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published'
        ]);

        // Handle video file upload
        if ($request->video_type === 'file' && $request->hasFile('video_file')) {
            $video = $request->file('video_file');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $videoPath = $video->storeAs('videos', $videoName, 'public');
            $validated['video_url'] = $videoPath;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '_thumb_' . $thumbnail->getClientOriginalName();
            $thumbnailPath = $thumbnail->storeAs('thumbnails', $thumbnailName, 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        Video::create($validated);

        return redirect()->route('admin.video.index')
            ->with('success', 'Video berhasil ditambahkan!');
    }

    /**
     * Display the specified resource (for AJAX modal)
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);
        
        return response()->json([
            'id' => $video->id,
            'judul' => $video->judul,
            'kategori' => $video->kategori,
            'video_url' => $video->video_url,
            'video_type' => $video->video_type,
            'embed_url' => $video->embed_url,
            'thumbnail' => $video->thumbnail,
            'thumbnail_url' => $video->thumbnail_url,
            'status' => $video->status,
            'created_at' => $video->created_at->format('d M Y H:i')
        ]);
    }

    /**
     * Show the form for editing the specified resource (for AJAX modal)
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        
        return response()->json([
            'id' => $video->id,
            'judul' => $video->judul,
            'kategori' => $video->kategori,
            'video_url' => $video->video_url,
            'video_type' => $video->video_type,
            'thumbnail' => $video->thumbnail,
            'thumbnail_url' => $video->thumbnail_url,
            'status' => $video->status
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,pengumuman,tutorial,dokumentasi',
            'video_type' => 'required|in:youtube,file',
            'video_url' => 'required_if:video_type,youtube|nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:20480',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published'
        ]);

        // Handle video file upload if new file provided
        if ($request->video_type === 'file' && $request->hasFile('video_file')) {
            // Delete old video file
            if ($video->video_type === 'file' && $video->video_url && Storage::disk('public')->exists($video->video_url)) {
                Storage::disk('public')->delete($video->video_url);
            }

            $videoFile = $request->file('video_file');
            $videoName = time() . '_' . $videoFile->getClientOriginalName();
            $videoPath = $videoFile->storeAs('videos', $videoName, 'public');
            $validated['video_url'] = $videoPath;
        } elseif ($request->video_type === 'youtube') {
            // If switching to YouTube, delete old video file
            if ($video->video_type === 'file' && $video->video_url && Storage::disk('public')->exists($video->video_url)) {
                Storage::disk('public')->delete($video->video_url);
            }
        }

        // Handle thumbnail upload if new thumbnail provided
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '_thumb_' . $thumbnail->getClientOriginalName();
            $thumbnailPath = $thumbnail->storeAs('thumbnails', $thumbnailName, 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        $video->update($validated);

        return redirect()->route('admin.video.index')
            ->with('success', 'Video berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            
            // Delete video file from storage if type is file
            if ($video->video_type === 'file' && $video->video_url && Storage::disk('public')->exists($video->video_url)) {
                Storage::disk('public')->delete($video->video_url);
            }

            // Delete thumbnail from storage
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            $video->delete();

            return redirect()->route('admin.video.index')
                ->with('success', 'Video berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.video.index')
                ->with('error', 'Gagal menghapus video: ' . $e->getMessage());
        }
    }
}