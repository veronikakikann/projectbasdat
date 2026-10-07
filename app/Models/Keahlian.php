<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    use HasFactory;

    protected $table = 'keahlian';

    protected $primaryKey = 'id_keahlian';

    public $timestamps = false;

    protected $fillable = [
        'nama_keahlian',
        'deskripsi',
    ];

    // Relasi ke pencari kerja melalui tabel pengajuan keahlian
    public function pencariKerja()
    {
        return $this->belongsToMany(
            PencariKerja::class,
            'keahlian_pencari_kerja',
            'id_keahlian',
            'id_pencari'
        )->withPivot(
            'id_keahlian_pencari',
            'judul_keahlian',
            'deskripsi_keahlian',
            'file_surat_rekomendasi',
            'status_verifikasi_keahlian',
            'tanggal_upload'
        );
    }

    // Semua pengajuan pencari yang menggunakan
    // keahlian dari master ini
    public function pengajuanPencari()
    {
        return $this->hasMany(
            KeahlianPencariKerja::class,
            'id_keahlian',
            'id_keahlian'
        );
    }

    // Keahlian yang digunakan pada pekerjaan
    public function pekerjaan()
    {
        return $this->hasMany(
            Pekerjaan::class,
            'id_keahlian',
            'id_keahlian'
        );
    }
}
