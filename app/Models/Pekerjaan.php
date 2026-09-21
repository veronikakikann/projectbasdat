<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'pekerjaan';
    protected $primaryKey = 'id_pekerjaan';
    public $timestamps = false;

    protected $fillable = [
        'id_pemberi',
        'id_keahlian',
        'deskripsi',
        'upah',
        'lokasi',
        'latitude',
        'longitude',
        'tanggal_pengerjaan',
        'status_pekerjaan',
        'tanggal_posting',
    ];

    public function pemberiKerja()
    {
        return $this->belongsTo(PemberiKerja::class, 'id_pemberi', 'id_pemberi');
    }

    public function keahlian()
    {
        return $this->belongsTo(Keahlian::class, 'id_keahlian', 'id_keahlian');
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class, 'id_pekerjaan', 'id_pekerjaan');
    }
}