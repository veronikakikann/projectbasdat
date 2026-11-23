<?php

namespace Tests\Feature;

use App\Models\BuktiPenyelesaian;
use App\Models\Lamaran;
use App\Models\PemberiKerja;
use App\Models\PencariKerja;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LamaranChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_accepted_participants_exchange_messages_and_view_work_and_payment_proof(): void
    {
        Storage::fake('local');
        $lamaran = Lamaran::factory()->create(['status_lamaran' => 'diterima']);
        $lamaran->pekerjaan->update(['status_pekerjaan' => 'sedang_dikerjakan']);
        $bukti = BuktiPenyelesaian::factory()->for($lamaran, 'lamaran')->create([
            'foto_bukti_kerja' => 'bukti/pengerjaan/kerja.pdf',
            'foto_bukti_bayar' => 'bukti/pembayaran/bayar.pdf',
            'catatan' => 'Pekerjaan sudah selesai.',
        ]);
        Storage::disk('local')->put($bukti->foto_bukti_kerja, '%PDF-1.4 bukti kerja');
        Storage::disk('local')->put($bukti->foto_bukti_bayar, '%PDF-1.4 bukti bayar');
        $room = route('lamaran.chat.show', $lamaran);
        $feed = route('lamaran.chat.pesan', $lamaran);
        $send = route('lamaran.chat.store', $lamaran);
        $workProof = route('dokumen.bukti', [$bukti, 'kerja']);
        $paymentProof = route('dokumen.bukti', [$bukti, 'bayar']);

        $this->loginAs('pemberi_kerja', $lamaran->pekerjaan->pemberiKerja);
        $this->get(route('pemberi.pelamar.index', $lamaran->id_pekerjaan))
            ->assertOk()->assertSee($room)->assertDontSee($lamaran->pencariKerja->email);
        $this->get(route('pemberi.pekerjaan.show', $lamaran->id_pekerjaan))
            ->assertOk()->assertSee($room)->assertSee($workProof)->assertSee($paymentProof);
        $this->get(route('pemberi.bukti.create', $lamaran->id_pekerjaan))
            ->assertOk()->assertSee($workProof)->assertSee('Pekerjaan sudah selesai.');
        $this->get($room)->assertOk()->assertSee('Belum ada pesan.')->assertSee($workProof)->assertSee($paymentProof);
        $this->post($send, ['pesan' => '   '])->assertSessionHasErrors('pesan');
        $this->post($send, ['pesan' => 'Jadwalnya besok ya.'])->assertRedirect($room);
        $this->get($workProof)->assertOk()->assertStreamedContent('%PDF-1.4 bukti kerja');
        $this->get($paymentProof)->assertOk();

        $this->loginAs('pencari_kerja', $lamaran->pencariKerja);
        foreach ([route('pencari.lamaran-saya'), route('pekerjaan.show', $lamaran->id_pekerjaan)] as $url) {
            $this->get($url)->assertOk()->assertSee($room)->assertSee($workProof)->assertSee($paymentProof)
                ->assertDontSee($lamaran->pekerjaan->pemberiKerja->email);
        }
        $this->get($room)->assertOk()->assertSee('Jadwalnya besok ya.')->assertSee($workProof)->assertSee($paymentProof);
        $this->post($send, ['pesan' => 'Siap! <b>Terima kasih</b>'])->assertRedirect($room);
        $this->get($workProof)->assertOk();
        $this->get($paymentProof)->assertOk()->assertStreamedContent('%PDF-1.4 bukti bayar');

        $lamaran->update(['status_lamaran' => 'selesai']);
        $this->loginAs('pemberi_kerja', $lamaran->pekerjaan->pemberiKerja);
        $this->get(route('pemberi.bukti.create', $lamaran->id_pekerjaan))
            ->assertOk()->assertSee($paymentProof);
        $this->get($room)->assertOk()->assertSee('Siap! <b>Terima kasih</b>');
        $this->get($feed)->assertOk()->assertSee('Siap! <b>Terima kasih</b>')->assertDontSee('<b>Terima kasih</b>', false);
    }

    public function test_chat_is_closed_until_acceptance_and_denies_unrelated_accounts(): void
    {
        $lamaran = Lamaran::factory()->create();
        $room = route('lamaran.chat.show', $lamaran);
        $feed = route('lamaran.chat.pesan', $lamaran);
        $send = route('lamaran.chat.store', $lamaran);

        $this->loginAs('pencari_kerja', $lamaran->pencariKerja);
        $this->get($room)->assertForbidden();
        $this->post($send, ['pesan' => 'Halo'])->assertForbidden();
        $lamaran->update(['status_lamaran' => 'diterima']);

        $this->loginAs('pencari_kerja', PencariKerja::factory()->create());
        $this->get($room)->assertForbidden();
        $this->get($feed)->assertForbidden();
        $this->post($send, ['pesan' => 'Halo'])->assertForbidden();

        $this->loginAs('pemberi_kerja', PemberiKerja::factory()->create());
        $this->get($room)->assertForbidden();
        $this->get($feed)->assertForbidden();
        $this->post($send, ['pesan' => 'Halo'])->assertForbidden();
        $this->assertDatabaseCount('pesan_lamaran', 0);
    }

    private function loginAs(string $role, Model $account): void
    {
        $this->withSession([
            'login' => true,
            'role' => $role,
            'user_id' => $account->getKey(),
            'user_name' => $account->nama,
        ]);
    }
}
