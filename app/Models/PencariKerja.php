<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencariKerja extends Model
{
    use HasFactory;

    protected $table = 'pencari_kerja';

    protected $primaryKey = 'id_pencari';

    public $timestamps = false;

    protected $fillable = [
        'nik',
        'file_ktp',
        'nama',
        'alamat',
        'no_telpon',
        'email',
        'password',
        'foto_profil',
        'latitude',
        'longitude',
        'file_surat_pengantar',
        'status_verifikasi',
        'status_akun',
        'id_admin',
        'tanggal_daftar',
    ];

    // Admin yang melakukan verifikasi
    public function admin()
    {
        return $this->belongsTo(
            Admin::class,
            'id_admin',
            'id_admin'
        );
    }

    // Relasi ke master keahlian
    public function keahlian()
    {
        return $this->belongsToMany(
            Keahlian::class,
            'keahlian_pencari_kerja',
            'id_pencari',
            'id_keahlian'
        )->withPivot(
            'id_keahlian_pencari',
            'judul_keahlian',
            'deskripsi_keahlian',
            'file_surat_rekomendasi',
            'status_verifikasi_keahlian',
            'tanggal_upload'
        );
    }

    // Semua pengajuan keahlian milik pencari
    public function pengajuanKeahlian()
    {
        return $this->hasMany(
            KeahlianPencariKerja::class,
            'id_pencari',
            'id_pencari'
        );
    }

    // Semua lamaran yang dibuat pencari
    public function lamaran()
    {
        return $this->hasMany(
            Lamaran::class,
            'id_pencari',
            'id_pencari'
        );
    }
}
