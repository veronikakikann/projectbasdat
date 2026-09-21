<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    protected $table = 'keahlian';
    protected $primaryKey = 'id_keahlian';
    public $timestamps = false;

    protected $fillable = [
        'nama_keahlian',
        'deskripsi',
    ];

    public function pencariKerja()
    {
        return $this->belongsToMany(PencariKerja::class, 'keahlian_pencari_kerja', 'id_keahlian', 'id_pencari')
            ->withPivot('file_surat_rekomendasi', 'status_verifikasi_keahlian', 'tanggal_upload');
    }

    public function pekerjaan()
    {
        return $this->hasMany(Pekerjaan::class, 'id_keahlian', 'id_keahlian');
    }
}