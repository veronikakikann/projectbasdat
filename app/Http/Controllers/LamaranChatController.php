<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LamaranChatController extends Controller
{
    public function show(Lamaran $lamaran): View
    {
        $role = $this->authorizeParticipant($lamaran);
        $lamaran->loadMissing(['pencariKerja', 'pekerjaan.pemberiKerja', 'buktiPenyelesaian']);

        return view('lamaran-chat.show', [
            'lamaran' => $lamaran,
            'role' => $role,
            'messages' => $this->messages($lamaran),
        ]);
    }

    public function pesan(Lamaran $lamaran): Response
    {
        $role = $this->authorizeParticipant($lamaran);

        return response()->view('lamaran-chat.pesan', [
            'lamaran' => $lamaran,
            'messages' => $this->messages($lamaran),
            'role' => $role,
        ])->header('Cache-Control', 'private, no-store');
    }

    public function store(Request $request, Lamaran $lamaran): RedirectResponse
    {
        $role = $this->authorizeParticipant($lamaran);
        $data = $request->validate([
            'pesan' => ['required', 'string', 'max:2000'],
        ]);

        DB::table('pesan_lamaran')->insert([
            'id_lamaran' => $lamaran->getKey(),
            'peran_pengirim' => $role,
            'pesan' => $data['pesan'],
            'dikirim_pada' => now(),
        ]);

        return to_route('lamaran.chat.show', $lamaran);
    }

    private function authorizeParticipant(Lamaran $lamaran): string
    {
        $lamaran->loadMissing('pekerjaan');
        $role = session('role');
        $userId = (int) session('user_id');
        $isParticipant = match ($role) {
            'pemberi_kerja' => $userId === (int) $lamaran->pekerjaan?->id_pemberi,
            'pencari_kerja' => $userId === (int) $lamaran->id_pencari,
            default => false,
        };

        abort_unless(
            $userId > 0 && $isParticipant && in_array($lamaran->status_lamaran, ['diterima', 'selesai'], true),
            403
        );

        return $role;
    }

    private function messages(Lamaran $lamaran): CursorPaginator
    {
        return DB::table('pesan_lamaran')
            ->where('id_lamaran', $lamaran->getKey())
            ->orderByDesc('id_pesan')
            ->cursorPaginate(50);
    }
}
