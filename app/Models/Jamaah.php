<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    protected $fillable = [
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

    // Scope untuk filter
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

    public function getSisaPembayaranAttribute()
    {
        return $this->total_biaya - $this->terbayar;
    }

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

    public static function findForEdit($id)
    {
        return self::findOrFail($id);
    }

}