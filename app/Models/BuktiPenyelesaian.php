<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuktiPenyelesaian extends Model
{
    protected $table = 'bukti_penyelesaian';
    protected $primaryKey = 'id_bukti';
    public $timestamps = false;

    protected $fillable = [
        'id_lamaran',
        'foto_bukti_kerja',
        'foto_bukti_bayar',
        'catatan',
        'tanggal_upload',
    ];

    public function lamaran()
    {
        return $this->belongsTo(Lamaran::class, 'id_lamaran', 'id_lamaran');
    }
}