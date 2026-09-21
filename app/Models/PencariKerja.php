<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PencariKerja extends Model
{
    protected $table = 'pencari_kerja';
    protected $primaryKey = 'id_pencari';
    public $timestamps = false;

    protected $fillable = [
        'nik',
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
        'id_admin',
        'tanggal_daftar',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public function keahlian()
    {
        return $this->belongsToMany(Keahlian::class, 'keahlian_pencari_kerja', 'id_pencari', 'id_keahlian')
            ->withPivot('file_surat_rekomendasi', 'status_verifikasi_keahlian', 'tanggal_upload');
    }
}