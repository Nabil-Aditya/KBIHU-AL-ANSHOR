<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Jamaah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class JamaahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jamaah::query()->with('user');
        
        // Filter berdasarkan jenis ibadah
        if ($request->has('jenis_ibadah') && $request->jenis_ibadah != 'semua') {
            $query->where('jenis_ibadah', $request->jenis_ibadah);
        }
        
        // Filter berdasarkan status pendaftaran
        if ($request->has('status_pendaftaran') && $request->status_pendaftaran != 'semua') {
            $query->where('status_pendaftaran', $request->status_pendaftaran);
        }
        
        // Filter berdasarkan status pembayaran
        if ($request->has('status_pembayaran') && $request->status_pembayaran != 'semua') {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%")
                  ->orWhere('no_paspor', 'like', "%$search%")
                  ->orWhere('no_telepon', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        
        $jamaahs = $query->latest()->paginate(10);
        
        // Statistik
        $totalHaji = Jamaah::haji()->count();
        $totalUmrah = Jamaah::umrah()->count();
        $totalHajiKhusus = Jamaah::hajiKhusus()->count();
        $totalLunas = Jamaah::where('status_pembayaran', 'lunas')->count();
        
        return view('admin.kelola-jamaah', compact(
            'jamaahs', 
            'totalHaji', 
            'totalUmrah', 
            'totalHajiKhusus',
            'totalLunas'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|size:16|unique:jamaahs',
            'no_paspor' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'jenis_ibadah' => 'nullable|in:haji,umrah,haji_khusus',
            'gelombang' => 'nullable|in:1,2',
            'paket' => 'nullable|string|max:100',
            'tahun_berangkat' => 'nullable|integer|min:2023|max:2100',
            'status_pendaftaran' => 'required|in:calon,terdaftar,berkas_ok,berangkat,selesai,batal',
            'status_pembayaran' => 'required|in:belum_bayar,dp,cicil,lunas',
            'total_biaya' => 'nullable|numeric|min:0',
            'uang_dp' => 'nullable|numeric|min:0',
            'terbayar' => 'nullable|numeric|min:0',
            'is_mahram' => 'boolean',
            'mahram_dengan' => 'nullable|string|max:255',
            'kelompok' => 'nullable|string|max:100',
            'pembimbing' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            // Password validation
            'password' => 'nullable|string|min:6',
        ]);
        
        $validated['is_mahram'] = $request->has('is_mahram');
        
        // Auto calculate terbayar based on status
        if ($request->status_pembayaran == 'dp' && $request->uang_dp > 0) {
            $validated['terbayar'] = $request->uang_dp;
        } elseif ($request->status_pembayaran == 'lunas') {
            $validated['terbayar'] = $request->total_biaya;
        }
        
        DB::beginTransaction();
        
        try {
            // 1. Create user account
            $userData = [
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : Hash::make('password123'),
                'role' => 'jamaah',
            ];
            
            $user = User::create($userData);
            
            // 2. Save jamaah data with user_id
            $validated['user_id'] = $user->id;
            Jamaah::create($validated);
            
            DB::commit();
            
            return redirect()->route('admin.jamaah.index')
                ->with('success', 'Data jamaah dan akun berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jamaah = Jamaah::with('user')->findOrFail($id);
        return response()->json($jamaah);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jamaah = Jamaah::with('user')->findOrFail($id);
        return response()->json($jamaah);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jamaah = Jamaah::with('user')->findOrFail($id);
        
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|size:16|unique:jamaahs,nik,' . $jamaah->id,
            'no_paspor' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . ($jamaah->user ? $jamaah->user->id : 'NULL'),
            'jenis_ibadah' => 'nullable|in:haji,umrah,haji_khusus',
            'gelombang' => 'nullable|in:1,2',
            'paket' => 'nullable|string|max:100',
            'tahun_berangkat' => 'nullable|integer|min:2023|max:2100',
            'status_pendaftaran' => 'required|in:calon,terdaftar,berkas_ok,berangkat,selesai,batal',
            'status_pembayaran' => 'required|in:belum_bayar,dp,cicil,lunas',
            'total_biaya' => 'nullable|numeric|min:0',
            'uang_dp' => 'nullable|numeric|min:0',
            'terbayar' => 'nullable|numeric|min:0',
            'is_mahram' => 'boolean',
            'mahram_dengan' => 'nullable|string|max:255',
            'kelompok' => 'nullable|string|max:100',
            'pembimbing' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            // Password validation
            'password' => 'nullable|string|min:6',
        ]);
        
        $validated['is_mahram'] = $request->has('is_mahram');
        
        DB::beginTransaction();
        
        try {
            // Update jamaah data
            $jamaah->update($validated);
            
            // Update or create user
            if ($jamaah->user) {
                // Update existing user
                $userData = [
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                ];
                
                // Update password if provided
                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }
                
                $jamaah->user->update($userData);
            } else {
                // Create new user if not exists
                $userData = [
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'password' => $validated['password'] ? Hash::make($validated['password']) : Hash::make('password123'),
                    'role' => 'jamaah',
                ];
                
                $user = User::create($userData);
                $jamaah->update(['user_id' => $user->id]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.jamaah.index')
                ->with('success', 'Data jamaah dan akun berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jamaah = Jamaah::with('user')->findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // Delete user if exists
            if ($jamaah->user) {
                $jamaah->user->delete();
            }
            
            // Delete jamaah
            $jamaah->delete();
            
            DB::commit();
            
            return redirect()->route('admin.jamaah.index')
                ->with('success', 'Data jamaah dan akun berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export data to Excel
     */
    public function export(Request $request)
    {
        $query = Jamaah::query();
        
        if ($request->jenis_ibadah && $request->jenis_ibadah != 'semua') {
            $query->where('jenis_ibadah', $request->jenis_ibadah);
        }
        
        $jamaahs = $query->get();
        
        return response()->streamDownload(function() use ($jamaahs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Nama Lengkap', 'NIK', 'No Paspor', 'Jenis Kelamin', 
                'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'No Telepon',
                'Email', 'Jenis Ibadah', 'Gelombang', 'Paket', 'Tahun Berangkat',
                'Status Pendaftaran', 'Status Pembayaran', 'Total Biaya', 
                'Uang DP', 'Terbayar', 'Sisa', 'Kelompok', 'Pembimbing', 'Keterangan'
            ]);
            
            foreach ($jamaahs as $jamaah) {
                fputcsv($handle, [
                    $jamaah->nama_lengkap,
                    $jamaah->nik,
                    $jamaah->no_paspor,
                    $jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    $jamaah->tempat_lahir,
                    $jamaah->tanggal_lahir->format('Y-m-d'),
                    $jamaah->alamat,
                    $jamaah->no_telepon,
                    $jamaah->email,
                    $jamaah->jenis_ibadah == 'haji' ? 'Haji' : ($jamaah->jenis_ibadah == 'umrah' ? 'Umrah' : 'Haji Khusus'),
                    $jamaah->gelombang,
                    $jamaah->paket,
                    $jamaah->tahun_berangkat,
                    ucfirst(str_replace('_', ' ', $jamaah->status_pendaftaran)),
                    ucfirst(str_replace('_', ' ', $jamaah->status_pembayaran)),
                    number_format($jamaah->total_biaya, 0, ',', '.'),
                    number_format($jamaah->uang_dp, 0, ',', '.'),
                    number_format($jamaah->terbayar, 0, ',', '.'),
                    number_format($jamaah->sisa_pembayaran, 0, ',', '.'),
                    $jamaah->kelompok,
                    $jamaah->pembimbing,
                    $jamaah->keterangan
                ]);
            }
            
            fclose($handle);
        }, 'data-jamaah-' . date('Y-m-d') . '.csv');
    }
}