<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'id_rating';
    public $timestamps = false;

    // Rating hanya boleh diedit dalam X jam setelah dibuat
    public const BATAS_EDIT_JAM = 24;

    protected $fillable = [
        'id_lamaran',
        'pemberi_rating',
        'penerima_rating',
        'arah_rating',
        'skor',
        'kategori_komentar',
        'tanggal_rating',
    ];

    protected $casts = [
        'tanggal_rating' => 'datetime',
    ];

    public function bisaDiedit(): bool
    {
        return $this->tanggal_rating->copy()->addHours(self::BATAS_EDIT_JAM)->isFuture();
    }

    public function lamaran()
    {
        return $this->belongsTo(Lamaran::class, 'id_lamaran', 'id_lamaran');
    }
}
