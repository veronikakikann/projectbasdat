<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'pekerjaan';
    protected $primaryKey = 'id_pekerjaan';
    public $timestamps = false;

    // Label status untuk ditampilkan ke user (nilai di database tetap yang di kiri)
    public const LABEL_STATUS = [
        'tersedia'          => 'Aktif',
        'penuh'             => 'Penuh',
        'sedang_dikerjakan' => 'Sedang dikerjakan',
        'selesai'           => 'Selesai',
        'ditutup'           => 'Ditutup',
    ];

    protected $fillable = [
        'id_pemberi',
        'id_keahlian',
        'nama_pekerjaan',
        'deskripsi',
        'upah',
        'lokasi',
        'latitude',
        'longitude',
        'tanggal_pengerjaan',
        'jumlah_pekerja',
        'persyaratan',
        'status_pekerjaan',
        'tanggal_posting',
    ];

    public function getLabelStatusAttribute(): string
    {
        return self::LABEL_STATUS[$this->status_pekerjaan] ?? $this->status_pekerjaan;
    }

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

    // Jumlah pekerja yang sudah diterima (lamaran jadi 'selesai' setelah pekerjaan selesai, tetap dihitung)
    public function jumlahDiterima(): int
    {
        return $this->lamaran()->whereIn('status_lamaran', ['diterima', 'selesai'])->count();
    }

    // Tolak semua pelamar yang masih menunggu + kirim notifikasi ke masing-masing
    public function tolakPelamarMenunggu(string $alasan): int
    {
        $menunggu = $this->lamaran()->where('status_lamaran', 'menunggu')->get();

        foreach ($menunggu as $lamaran) {
            $lamaran->update(['status_lamaran' => 'ditolak']);

            Notifikasi::kirim(
                $lamaran->id_pencari,
                'pencari_kerja',
                'Lamaranmu untuk "' . $this->nama_pekerjaan . '" ditolak karena ' . $alasan . '.'
            );
        }

        return $menunggu->count();
    }
}
