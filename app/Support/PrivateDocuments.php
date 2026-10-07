<?php

namespace App\Support;

use App\Models\BuktiPenyelesaian;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivateDocuments
{
    public static function store(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'local');
    }

    public static function response(?string $path): StreamedResponse
    {
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public static function deleteUnused(?string $path): void
    {
        if (! $path) {
            return;
        }

        // Older records may share one proof file across several workers.
        if (BuktiPenyelesaian::where('foto_bukti_kerja', $path)
            ->orWhere('foto_bukti_bayar', $path)->exists()) {
            return;
        }

        Storage::disk('local')->delete($path);
    }
}
