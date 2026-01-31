<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jamaah extends Model
{
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik',
        'no_paspor',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'email',
        'jenis_ibadah',
        'gelombang',
        'paket',
        'tahun_berangkat',
        'status_pendaftaran',
        'status_pembayaran',
        'total_biaya',
        'uang_dp',
        'terbayar',
        'is_mahram',
        'mahram_dengan',
        'kelompok',
        'pembimbing',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_mahram' => 'boolean',
        'total_biaya' => 'decimal:2',
        'uang_dp' => 'decimal:2',
        'terbayar' => 'decimal:2'
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    /**
     * Scope untuk mencari jamaah yang belum memiliki user
     */
    public function scopeWithoutUser($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope untuk filter jenis ibadah
     */
    public function scopeHaji($query)
    {
        return $query->where('jenis_ibadah', 'haji');
    }

    public function scopeUmrah($query)
    {
        return $query->where('jenis_ibadah', 'umrah');
    }

    public function scopeHajiKhusus($query)
    {
        return $query->where('jenis_ibadah', 'haji_khusus');
    }

    /**
     * Accessor untuk sisa pembayaran
     */
    public function getSisaPembayaranAttribute()
    {
        return $this->total_biaya - $this->terbayar;
    }

    /**
     * Accessor untuk warna status pembayaran
     */
    public function getStatusPembayaranColorAttribute()
    {
        $colors = [
            'belum_bayar' => 'danger',
            'dp' => 'warning',
            'cicil' => 'info',
            'lunas' => 'success'
        ];
        
        return $colors[$this->status_pembayaran] ?? 'secondary';
    }

    /**
     * Method untuk membuat user jika belum ada
     */
    public function createUserAccount($password = null)
    {
        if ($this->user) {
            return $this->user;
        }

        $user = User::create([
            'name' => $this->nama_lengkap,
            'email' => $this->email,
            'password' => $password ? \Hash::make($password) : \Hash::make('password123'),
            'role' => 'jamaah',
        ]);

        $this->update(['user_id' => $user->id]);

        return $user;
    }

    /**
     * Method untuk update user terkait
     */
    public function updateUserAccount(array $data = [])
    {
        if (!$this->user) {
            return $this->createUserAccount();
        }

        $updateData = [];
        
        // Update name jika berbeda
        if ($this->nama_lengkap !== $this->user->name) {
            $updateData['name'] = $this->nama_lengkap;
        }
        
        // Update email jika berbeda
        if ($this->email !== $this->user->email) {
            $updateData['email'] = $this->email;
        }
        
        // Update password jika ada
        if (!empty($data['password'])) {
            $updateData['password'] = \Hash::make($data['password']);
        }
        
        if (!empty($updateData)) {
            $this->user->update($updateData);
        }
        
        return $this->user;
    }

    /**
     * Override delete untuk menghapus user juga
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($jamaah) {
            if ($jamaah->user) {
                $jamaah->user->delete();
            }
        });
    }

    /**
     * Cari untuk edit - tetap dipertahankan untuk kompatibilitas
     */
    public static function findForEdit($id)
    {
        return self::with('user')->findOrFail($id);
    }
}