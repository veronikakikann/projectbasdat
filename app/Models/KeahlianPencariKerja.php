<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianPencariKerja extends Model
{
    use HasFactory;

    protected $table = 'keahlian_pencari_kerja';

    protected $primaryKey = 'id_keahlian_pencari';

    public $timestamps = false;

    protected $fillable = [
        'id_pencari',
        'judul_keahlian',
        'deskripsi_keahlian',
        'id_keahlian',
        'file_surat_rekomendasi',
        'status_verifikasi_keahlian',
        'tanggal_upload',
    ];

    public function pencariKerja()
    {
        return $this->belongsTo(
            PencariKerja::class,
            'id_pencari',
            'id_pencari'
        );
    }

    public function keahlian()
    {
        return $this->belongsTo(
            Keahlian::class,
            'id_keahlian',
            'id_keahlian'
        );
    }
}
