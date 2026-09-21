<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemberiKerja extends Model
{
    protected $table = 'pemberi_kerja';
    protected $primaryKey = 'id_pemberi';
    public $timestamps = false;

    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'no_telpon',
        'email',
        'password',
        'foto_profil',
        'status_verifikasi',
        'id_admin',
        'tanggal_daftar',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}