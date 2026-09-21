<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lamaran extends Model
{
    protected $table = 'lamaran';
    protected $primaryKey = 'id_lamaran';
    public $timestamps = false;

    protected $fillable = [
        'id_pekerjaan',
        'id_pencari',
        'status_lamaran',
        'tanggal_submit',
    ];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'id_pekerjaan', 'id_pekerjaan');
    }

    public function pencariKerja()
    {
        return $this->belongsTo(PencariKerja::class, 'id_pencari', 'id_pencari');
    }

    public function buktiPenyelesaian()
    {
        return $this->hasOne(BuktiPenyelesaian::class, 'id_lamaran', 'id_lamaran');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'id_lamaran', 'id_lamaran');
    }
}