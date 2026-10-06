<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'tipe_user',
        'isi_pesan',
        'status_baca',
        'tanggal',
    ];

    // Cara pakai di controller mana pun:
    //   Notifikasi::kirim($idPemberi, 'pemberi_kerja', 'Ada pelamar baru ...');
    //   Notifikasi::kirim($idPencari, 'pencari_kerja', 'Lamaranmu diterima ...');
    public static function kirim(int $idUser, string $tipeUser, string $pesan): self
    {
        return static::create([
            'id_user'   => $idUser,
            'tipe_user' => $tipeUser,
            'isi_pesan' => $pesan,
        ]);
    }
}
