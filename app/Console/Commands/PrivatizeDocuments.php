<?php

namespace App\Console\Commands;

use App\Models\BuktiPenyelesaian;
use App\Models\KeahlianPencariKerja;
use App\Models\PemberiKerja;
use App\Models\PencariKerja;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PrivatizeDocuments extends Command
{
    protected $signature = 'documents:privatize';

    protected $description = 'Pindahkan dokumen lama dari storage public ke storage privat tanpa mengubah referensi database';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');
        $definitions = [
            PemberiKerja::class => ['file_ktp'],
            PencariKerja::class => ['file_ktp', 'file_surat_pengantar'],
            KeahlianPencariKerja::class => ['file_surat_rekomendasi'],
            BuktiPenyelesaian::class => ['foto_bukti_kerja', 'foto_bukti_bayar'],
        ];
        $count = 0;
        foreach ($definitions as $model => $fields) {
            foreach ($model::cursor() as $record) {
                foreach ($fields as $field) {
                    $path = $record->$field;
                    if (! $path || ! $public->exists($path)) {
                        continue;
                    }
                    $contents = $public->get($path);
                    if (! $private->exists($path)) {
                        $private->put($path, $contents);
                    }
                    if (hash('sha256', $private->get($path)) !== hash('sha256', $contents)) {
                        $this->error('Dokumen privat berbeda dari sumber. File publik dipertahankan: '.$path);

                        return self::FAILURE;
                    }
                    if (! $public->delete($path) || $public->exists($path)) {
                        $this->error('Dokumen sudah disalin tetapi sumber publik gagal dihapus: '.$path);

                        return self::FAILURE;
                    }
                    $count++;
                }
            }
        }
        $this->info($count.' dokumen dipindahkan ke penyimpanan privat.');

        return self::SUCCESS;
    }
}
