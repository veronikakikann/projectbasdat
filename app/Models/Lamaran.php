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

    /**
     * Lamaran memiliki satu pekerjaan.
     */
    public function pekerjaan()
    {
        return $this->belongsTo(
            Pekerjaan::class,
            'id_pekerjaan',
            'id_pekerjaan'
        );
    }

    /**
     * Lamaran dimiliki oleh satu pencari kerja.
     */
    public function pencariKerja()
    {
        return $this->belongsTo(
            PencariKerja::class,
            'id_pencari',
            'id_pencari'
        );
    }

    /**
     * Satu lamaran memiliki satu bukti penyelesaian.
     *
     * Bukti ini menyimpan:
     * - foto_bukti_kerja → diupload Pencari
     * - foto_bukti_bayar → diupload Pemberi
     */
    public function buktiPenyelesaian()
    {
        return $this->hasOne(
            BuktiPenyelesaian::class,
            'id_lamaran',
            'id_lamaran'
        );
    }

    /**
     * Satu lamaran dapat memiliki beberapa rating.
     *
     * Contoh:
     * - pekerja → pemberi
     * - pemberi → pekerja
     */
    public function rating()
    {
        return $this->hasMany(
            Rating::class,
            'id_lamaran',
            'id_lamaran'
        );
    }
}