<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\BuktiPenyelesaian;
use App\Models\Keahlian;
use App\Models\KeahlianPencariKerja;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use App\Models\PemberiKerja;
use App\Models\PencariKerja;
use App\Models\Rating;
use Database\Seeders\DemoPemberiSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoleCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('public');
    }

    private function loginAs(string $role, Model $account): void
    {
        $this->withSession(['login' => true, 'role' => $role, 'user_id' => $account->getKey(), 'user_name' => $account->nama]);
    }

    private function accountData(array $overrides = []): array
    {
        return array_replace([
            'nik' => fake()->unique()->numerify('################'),
            'nama' => 'Akun Uji',
            'alamat' => 'Jalan Pengujian',
            'no_telpon' => '08123456789',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status_verifikasi' => 'terverifikasi',
            'status_akun' => 'aktif',
            'tanggal_daftar' => now()->toDateString(),
        ], $overrides);
    }

    private function jobData(Keahlian $skill, array $overrides = []): array
    {
        return array_replace([
            'nama_pekerjaan' => 'Servis AC',
            'id_keahlian' => $skill->id_keahlian,
            'deskripsi' => 'Membersihkan AC',
            'lokasi' => 'Surabaya',
            'tanggal_pengerjaan' => now()->addDay()->toDateString(),
            'jumlah_pekerja' => 2,
            'upah' => 150000,
        ], $overrides);
    }

    public function test_admin_pages_render_and_account_crud_preserves_identity(): void
    {
        $admin = Admin::factory()->create();
        $this->loginAs('admin', $admin);
        $this->get(route('admin.dashboard'))->assertOk();
        foreach (['admin', 'pemberi_kerja', 'pencari_kerja', 'keahlian'] as $resource) {
            $this->get(route($resource.'.index'))->assertOk();
            $this->get(route($resource.'.create'))->assertOk();
        }
        $this->get(route('admin.verifikasi-keahlian'))->assertOk();
        foreach (['pekerjaan', 'lamaran', 'bukti', 'rating', 'notifikasi'] as $kind) {
            $this->get(route('admin.transaksi.index', $kind))->assertOk();
        }

        foreach (['pemberi_kerja' => PemberiKerja::class, 'pencari_kerja' => PencariKerja::class] as $role => $model) {
            $data = $this->accountData();
            $this->post(route($role.'.store'), $data)->assertRedirect(route($role.'.index'))->assertSessionHasNoErrors();
            $account = $model::where('email', $data['email'])->firstOrFail();
            $this->assertTrue(Hash::check('password123', $account->password));
            $this->assertSame($admin->id_admin, $account->id_admin);
            $this->get(route($role.'.edit', $account))->assertOk()->assertSee('password_confirmation', false)->assertSee('status_akun', false);
            $data['nik'] = '1234567890123456';
            $data['nama'] = 'Akun diperbarui';
            $data['password'] = 'newpassword123';
            $data['password_confirmation'] = 'newpassword123';
            $data['status_akun'] = 'nonaktif';
            $this->put(route($role.'.update', $account), $data)->assertSessionHasNoErrors();
            $this->assertNotSame($data['nik'], $account->fresh()->nik);
            $this->assertSame('nonaktif', $account->fresh()->status_akun);
            $this->assertTrue(Hash::check('newpassword123', $account->fresh()->password));
            $this->delete(route($role.'.destroy', $account))->assertSessionHas('success');
            $this->assertNull($account->fresh());
        }

        $data = ['nama' => 'Admin baru', 'email' => 'newadmin@example.com', 'password' => 'password123',
            'password_confirmation' => 'password123', 'tanggal_bergabung' => now()->toDateString()];
        $this->post(route('admin.store'), $data)->assertSessionHasNoErrors();
        $other = Admin::where('email', $data['email'])->firstOrFail();
        $this->get(route('admin.edit', $other))->assertOk()->assertSee('password_confirmation', false);
        $data['nama'] = 'Admin diperbarui';
        $this->put(route('admin.update', $other), $data)->assertSessionHasNoErrors();
        $this->assertSame($data['nama'], $other->fresh()->nama);
        $this->delete(route('admin.destroy', $other))->assertSessionHas('success');
    }

    public function test_admin_cannot_delete_self_last_admin_or_accounts_with_history(): void
    {
        $admin = Admin::factory()->create();
        $this->loginAs('admin', $admin);
        $this->delete(route('admin.destroy', $admin))->assertSessionHas('error');
        $otherAdmin = Admin::factory()->create();
        $pemberi = PemberiKerja::factory()->create(['id_admin' => $otherAdmin->id_admin]);
        $job = Pekerjaan::factory()->create(['id_pemberi' => $pemberi->id_pemberi]);
        $worker = PencariKerja::factory()->create();
        Lamaran::factory()->create(['id_pekerjaan' => $job->id_pekerjaan, 'id_pencari' => $worker->id_pencari]);
        $this->delete(route('admin.destroy', $admin))->assertSessionHas('error');
        $this->delete(route('admin.destroy', $otherAdmin))->assertSessionHas('error');
        $this->delete(route('pemberi_kerja.destroy', $pemberi))->assertSessionHas('error');
        $this->delete(route('pencari_kerja.destroy', $worker))->assertSessionHas('error');
        $this->assertNotNull($pemberi->fresh());
        $this->assertNotNull($worker->fresh());
    }

    public function test_master_skill_crud_and_reference_protection(): void
    {
        $this->loginAs('admin', Admin::factory()->create());
        $this->post(route('keahlian.store'), ['nama_keahlian' => 'AC', 'deskripsi' => 'Teknisi'])->assertSessionHasNoErrors();
        $skill = Keahlian::where('nama_keahlian', 'AC')->firstOrFail();
        $this->get(route('keahlian.edit', $skill))->assertOk();
        $this->put(route('keahlian.update', $skill), ['nama_keahlian' => 'Servis AC'])->assertSessionHasNoErrors();
        $submission = KeahlianPencariKerja::factory()->create(['id_keahlian' => $skill->id_keahlian]);
        $this->delete(route('keahlian.destroy', $skill))->assertSessionHas('error');
        $submission->delete();
        $this->delete(route('keahlian.destroy', $skill))->assertSessionHas('success');
        $this->assertNull($skill->fresh());
    }

    public function test_job_crud_uses_session_owner_and_protects_foreign_records(): void
    {
        $employer = PemberiKerja::factory()->create();
        $other = PemberiKerja::factory()->create();
        $skill = Keahlian::factory()->create();
        $foreign = Pekerjaan::factory()->create(['id_pemberi' => $other->id_pemberi]);
        $this->loginAs('pemberi_kerja', $employer);
        foreach (['pemberi.dashboard', 'pemberi.pekerjaan.index', 'pemberi.pekerjaan.create',
            'pemberi.lamaran.index', 'pemberi.notifikasi.index', 'pemberi.profil.show', 'pemberi.profil.edit'] as $page) {
            $this->get(route($page))->assertOk();
        }
        $this->post(route('pemberi.pekerjaan.store'), $this->jobData($skill, [
            'id_pemberi' => $other->id_pemberi, 'status_pekerjaan' => 'selesai',
        ]))->assertSessionHasNoErrors();
        $job = Pekerjaan::where('id_pemberi', $employer->id_pemberi)->firstOrFail();
        $this->assertSame('tersedia', $job->status_pekerjaan);
        $this->get(route('pemberi.pekerjaan.show', $job))->assertOk();
        $this->get(route('pemberi.pekerjaan.edit', $job))->assertOk();
        $this->put(route('pemberi.pekerjaan.update', $job), $this->jobData($skill, ['nama_pekerjaan' => 'AC baru']))
            ->assertSessionHasNoErrors();
        $this->assertSame('AC baru', $job->fresh()->nama_pekerjaan);
        $this->get(route('pemberi.pekerjaan.show', $foreign))->assertForbidden();
        $this->put(route('pemberi.pekerjaan.update', $foreign), $this->jobData($skill))->assertForbidden();
        $this->delete(route('pemberi.pekerjaan.destroy', $foreign))->assertForbidden();
        $this->delete(route('pemberi.pekerjaan.destroy', $job))->assertSessionHas('success');
        $this->assertNull($job->fresh());
    }

    public function test_two_workers_complete_job_payment_and_both_rating_directions(): void
    {
        $employer = PemberiKerja::factory()->create();
        $job = Pekerjaan::factory()->create(['id_pemberi' => $employer->id_pemberi, 'jumlah_pekerja' => 2]);
        $workers = PencariKerja::factory()->count(2)->create();
        $applications = [];
        foreach ($workers as $worker) {
            $this->loginAs('pencari_kerja', $worker);
            $this->post(route('pencari.lamar', $job), ['id_pencari' => 999, 'status_lamaran' => 'diterima'])
                ->assertSessionHasNoErrors();
            $application = Lamaran::where('id_pencari', $worker->id_pencari)->firstOrFail();
            $this->assertSame('menunggu', $application->status_lamaran);
            $applications[] = $application;
        }
        $this->loginAs('pemberi_kerja', $employer);
        $this->get(route('pemberi.lamaran.index'))->assertOk()->assertSee('name="_method" value="PATCH"', false);
        foreach ($applications as $application) {
            $this->patch(route('pemberi.lamaran.update', $application), ['status_lamaran' => 'diterima'])
                ->assertSessionHas('success');
        }
        $this->assertSame('penuh', $job->fresh()->status_pekerjaan);
        $this->get(route('pemberi.pekerjaan.show', $job))->assertOk()->assertSee('Mulai Pekerjaan');
        $this->patch(route('pemberi.pekerjaan.mulai', $job))->assertSessionHas('success');

        foreach ($applications as $index => $application) {
            $this->loginAs('pencari_kerja', $workers[$index]);
            $this->get(route('pencari.bukti.create', $application))->assertOk();
            $this->post(route('pencari.bukti.store', $application), [
                'foto_bukti_kerja' => UploadedFile::fake()->create('kerja.pdf', 20, 'application/pdf'),
                'catatan' => 'Selesai dikerjakan',
            ])->assertSessionHasNoErrors();
            $proof = $application->buktiPenyelesaian()->firstOrFail();
            Storage::disk('local')->assertExists($proof->foto_bukti_kerja);
            Storage::disk('public')->assertMissing($proof->foto_bukti_kerja);
            $this->get(route('dokumen.bukti', [$proof, 'kerja']))->assertOk();
        }

        $this->loginAs('pemberi_kerja', $employer);
        $this->get(route('pemberi.bukti.create', $job))->assertOk()->assertSee('foto_bukti_bayar', false);
        foreach ($applications as $index => $application) {
            $this->post(route('pemberi.bukti.store', $job), [
                'id_lamaran' => $application->id_lamaran,
                'foto_bukti_bayar' => UploadedFile::fake()->create('bayar.pdf', 20, 'application/pdf'),
                'catatan_bayar' => 'Transfer diterima',
            ])->assertSessionHasNoErrors();
            $this->assertSame('selesai', $application->fresh()->status_lamaran);
            $this->assertSame($index === 0 ? 'sedang_dikerjakan' : 'selesai', $job->fresh()->status_pekerjaan);
            $proof = $application->buktiPenyelesaian()->firstOrFail();
            $this->assertSame('Transfer diterima', $proof->catatan_bayar);
            $this->get(route('pemberi.bukti.file', [$proof, 'bayar']))->assertOk();
            $this->get(route('pemberi.rating.form', $application))->assertOk()
                ->assertSee(route('pemberi.rating.store', $application), false);
            $this->post(route('pemberi.rating.store', $application), ['skor' => 5, 'kategori_komentar' => 'Bagus'])
                ->assertSessionHasNoErrors();
            $rating = Rating::where('id_lamaran', $application->id_lamaran)->firstOrFail();
            $this->get(route('pemberi.rating.form', $application))->assertOk();
            $this->put(route('pemberi.rating.update', $rating), ['skor' => 4, 'kategori_komentar' => 'Tepat waktu'])
                ->assertSessionHasNoErrors();
            $this->assertSame('Tepat waktu', $rating->fresh()->kategori_komentar);
        }
        $this->get(route('pemberi.pekerjaan.show', $job))->assertOk()->assertSee('Tepat waktu');
        $this->get(route('pemberi.bukti.create', $job))->assertOk();

        foreach ($applications as $index => $application) {
            $this->loginAs('pencari_kerja', $workers[$index]);
            $this->get(route('pencari.rating.form', $application))->assertOk();
            $this->post(route('pencari.rating.store', $application), ['skor' => 5, 'kategori_komentar' => 'Pembayaran lancar'])
                ->assertSessionHasNoErrors();
            $rating = Rating::where('id_lamaran', $application->id_lamaran)->where('arah_rating', 'pekerja_ke_pemberi')->firstOrFail();
            $this->put(route('pencari.rating.update', $rating), ['skor' => 4, 'kategori_komentar' => 'Baik'])->assertSessionHasNoErrors();
            $this->get(route('pencari.lamaran-saya'))->assertOk();
        }
        $this->loginAs('pemberi_kerja', $employer);
        $this->get(route('pemberi.profil.show'))->assertOk()->assertSee('Baik');
        $this->assertSame(4, Rating::count());
        $this->assertDatabaseHas('notifikasi', ['tipe_user' => 'pemberi_kerja', 'id_user' => $employer->id_pemberi]);
    }

    public function test_skill_submission_rejection_resubmission_and_admin_verification(): void
    {
        $worker = PencariKerja::factory()->create();
        $admin = Admin::factory()->create();
        $this->loginAs('pencari_kerja', $worker);
        $data = ['judul_keahlian' => 'Teknisi AC', 'deskripsi_keahlian' => 'Mampu memperbaiki AC',
            'file_surat_rekomendasi' => UploadedFile::fake()->create('surat.pdf', 10, 'application/pdf'),
            'id_pencari' => 999, 'status_verifikasi_keahlian' => 'terverifikasi'];
        $this->post(route('keahlian_pencari_kerja.store'), $data)->assertSessionHasNoErrors();
        $submission = KeahlianPencariKerja::firstOrFail();
        $this->assertSame($worker->id_pencari, $submission->id_pencari);
        $this->assertSame('menunggu', $submission->status_verifikasi_keahlian);
        $this->get(route('keahlian_pencari_kerja.edit', $submission))->assertOk();
        $this->get(route('dokumen.keahlian', $submission))->assertOk();

        $this->loginAs('admin', $admin);
        $this->get(route('admin.verifikasi-keahlian'))->assertOk();
        $this->patch(route('admin.verifikasi-keahlian.keputusan', $submission),
            ['status_verifikasi_keahlian' => 'terverifikasi'])->assertSessionHasErrors('id_keahlian');
        $this->patch(route('admin.verifikasi-keahlian.keputusan', $submission),
            ['status_verifikasi_keahlian' => 'ditolak'])->assertSessionHasNoErrors();

        $this->loginAs('pencari_kerja', $worker);
        $oldPath = $submission->file_surat_rekomendasi;
        $this->put(route('keahlian_pencari_kerja.update', $submission), [
            'judul_keahlian' => 'Teknisi AC diperbarui', 'deskripsi_keahlian' => 'Bukti baru',
            'file_surat_rekomendasi' => UploadedFile::fake()->create('baru.pdf', 10, 'application/pdf'),
        ])->assertSessionHasNoErrors();
        Storage::disk('local')->assertMissing($oldPath);
        $this->assertSame('menunggu', $submission->fresh()->status_verifikasi_keahlian);

        $this->loginAs('admin', $admin);
        $skill = Keahlian::factory()->create();
        $this->patch(route('admin.verifikasi-keahlian.keputusan', $submission), [
            'status_verifikasi_keahlian' => 'terverifikasi', 'id_keahlian' => $skill->id_keahlian,
        ])->assertSessionHasNoErrors();
        $this->assertSame($skill->id_keahlian, $submission->fresh()->id_keahlian);
        $this->loginAs('pencari_kerja', $worker);
        $this->put(route('keahlian_pencari_kerja.update', $submission), [
            'judul_keahlian' => 'Ubah', 'deskripsi_keahlian' => 'Ubah',
        ])->assertForbidden();
        $this->delete(route('keahlian_pencari_kerja.destroy', $submission))->assertForbidden();

        $pending = KeahlianPencariKerja::factory()->create(['id_pencari' => $worker->id_pencari, 'file_surat_rekomendasi' => 'surat/pending.pdf']);
        Storage::disk('local')->put('surat/pending.pdf', 'pending');
        $this->delete(route('keahlian_pencari_kerja.destroy', $pending))->assertSessionHas('success');
        Storage::disk('local')->assertMissing('surat/pending.pdf');
    }

    public function test_cross_account_access_and_role_escalation_are_rejected(): void
    {
        $application = Lamaran::factory()->create(['status_lamaran' => 'selesai']);
        $proof = BuktiPenyelesaian::factory()->create(['id_lamaran' => $application->id_lamaran, 'foto_bukti_kerja' => 'secret.pdf']);
        Storage::disk('local')->put('secret.pdf', 'secret');
        $submission = KeahlianPencariKerja::factory()->create(['id_pencari' => $application->id_pencari]);
        $rating = Rating::factory()->create(['id_lamaran' => $application->id_lamaran]);
        $this->loginAs('pencari_kerja', PencariKerja::factory()->create());
        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('dokumen.bukti', [$proof, 'kerja']))->assertForbidden();
        $this->get(route('dokumen.keahlian', $submission))->assertForbidden();
        $this->get(route('keahlian_pencari_kerja.edit', $submission))->assertNotFound();
        $this->delete(route('pencari.lamaran.batal', $application))->assertForbidden();
        $this->get(route('pencari.rating.form', $application))->assertForbidden();
        $this->put(route('pencari.rating.update', $rating), ['skor' => 1])->assertForbidden();
        $this->post(route('pencari.bukti.store', $application), [])->assertForbidden();
        $this->loginAs('pemberi_kerja', PemberiKerja::factory()->create());
        $this->get(route('pemberi.bukti.file', [$proof, 'kerja']))->assertForbidden();
        $this->patch(route('pemberi.lamaran.update', $application), ['status_lamaran' => 'diterima'])->assertForbidden();
        $this->get(route('pemberi.rating.form', $application))->assertForbidden();
        $this->put(route('pemberi.rating.update', $rating), ['skor' => 1])->assertForbidden();
    }

    public function test_duplicate_and_early_ratings_are_rejected_and_edit_expires(): void
    {
        $application = Lamaran::factory()->create(['status_lamaran' => 'diterima']);
        $this->loginAs('pemberi_kerja', $application->pekerjaan->pemberiKerja);
        $this->post(route('pemberi.rating.store', $application), ['skor' => 5])->assertForbidden();
        $application->update(['status_lamaran' => 'selesai']);
        $this->post(route('pemberi.rating.store', $application), ['skor' => 5])->assertSessionHasNoErrors();
        $this->post(route('pemberi.rating.store', $application), ['skor' => 1])->assertSessionHasErrors('skor');
        $rating = Rating::firstOrFail();
        $rating->update(['tanggal_rating' => now()->subHours(25)]);
        $this->put(route('pemberi.rating.update', $rating), ['skor' => 1])->assertSessionHasErrors('skor');
        $this->assertSame(5, $rating->fresh()->skor);
        $this->loginAs('pencari_kerja', $application->pencariKerja);
        $this->post(route('pencari.rating.store', $application), ['skor' => 4])->assertSessionHasNoErrors();
        $this->post(route('pencari.rating.store', $application), ['skor' => 1])->assertSessionHasErrors('skor');
        $workerRating = Rating::where('arah_rating', 'pekerja_ke_pemberi')->firstOrFail();
        $workerRating->update(['tanggal_rating' => now()->subHours(25)]);
        $this->put(route('pencari.rating.update', $workerRating), ['skor' => 1])->assertSessionHasErrors('skor');
    }

    public function test_cancel_only_waiting_duplicate_applications_and_recruitment_closure(): void
    {
        $application = Lamaran::factory()->create();
        $worker = $application->pencariKerja;
        $job = $application->pekerjaan;
        $this->loginAs('pencari_kerja', $worker);
        $this->post(route('pencari.lamar', $job))->assertSessionHas('error');
        $this->delete(route('pencari.lamaran.batal', $application))->assertSessionHas('success');
        $this->assertNull($application->fresh());
        $this->post(route('pencari.lamar', $job))->assertSessionHasNoErrors();
        $accepted = Lamaran::where('id_pencari', $worker->id_pencari)->firstOrFail();
        $accepted->update(['status_lamaran' => 'diterima']);
        $this->delete(route('pencari.lamaran.batal', $accepted))->assertSessionHas('error');
        $this->assertNotNull($accepted->fresh());

        $waiting = Lamaran::factory()->create();
        $this->loginAs('pemberi_kerja', $waiting->pekerjaan->pemberiKerja);
        $this->patch(route('pemberi.pekerjaan.tutup', $waiting->pekerjaan))->assertSessionHas('success');
        $this->assertSame('ditolak', $waiting->fresh()->status_lamaran);
        $this->assertSame('ditutup', $waiting->pekerjaan->fresh()->status_pekerjaan);
    }

    public function test_payout_before_work_proof_and_completed_work_resubmission_are_rejected(): void
    {
        $application = Lamaran::factory()->create(['status_lamaran' => 'diterima']);
        $job = $application->pekerjaan;
        $job->update(['status_pekerjaan' => 'sedang_dikerjakan']);
        $this->loginAs('pemberi_kerja', $job->pemberiKerja);
        $this->post(route('pemberi.bukti.store', $job), [
            'id_lamaran' => $application->id_lamaran,
            'foto_bukti_bayar' => UploadedFile::fake()->image('bayar.png'),
        ])->assertSessionHasErrors('foto_bukti_bayar');
        $this->assertCount(0, Storage::disk('local')->allFiles());
        $application->update(['status_lamaran' => 'selesai']);
        $this->loginAs('pencari_kerja', $application->pencariKerja);
        $this->post(route('pencari.bukti.store', $application), [
            'foto_bukti_kerja' => UploadedFile::fake()->image('kerja.png'),
        ])->assertSessionHasErrors('foto_bukti_kerja');
        $this->assertCount(0, Storage::disk('local')->allFiles());
    }

    public function test_failed_work_proof_notification_rolls_back_record_and_cleans_file(): void
    {
        $application = Lamaran::factory()->create(['status_lamaran' => 'diterima']);
        $application->pekerjaan->update(['status_pekerjaan' => 'sedang_dikerjakan']);
        $this->loginAs('pencari_kerja', $application->pencariKerja);
        Notifikasi::creating(function (): void {
            throw new \RuntimeException('Simulated notification failure');
        });
        try {
            $this->postJson(route('pencari.bukti.store', $application), [
                'foto_bukti_kerja' => UploadedFile::fake()->image('kerja.png'),
            ])->assertStatus(500);
        } finally {
            Notifikasi::flushEventListeners();
        }
        $this->assertDatabaseCount('bukti_penyelesaian', 0);
        $this->assertCount(0, Storage::disk('local')->allFiles());
    }

    public function test_failed_payment_preserves_work_proof_and_removes_new_payment_file(): void
    {
        $application = Lamaran::factory()->create(['status_lamaran' => 'diterima']);
        $job = $application->pekerjaan;
        $job->update(['status_pekerjaan' => 'sedang_dikerjakan']);
        $proof = BuktiPenyelesaian::factory()->create(['id_lamaran' => $application->id_lamaran, 'foto_bukti_kerja' => 'old.pdf']);
        Storage::disk('local')->put('old.pdf', 'original');
        $this->loginAs('pemberi_kerja', $job->pemberiKerja);
        Notifikasi::creating(function (): void {
            throw new \RuntimeException('Simulated notification failure');
        });
        try {
            $this->postJson(route('pemberi.bukti.store', $job), [
                'id_lamaran' => $application->id_lamaran,
                'foto_bukti_bayar' => UploadedFile::fake()->image('bayar.png'),
            ])->assertStatus(500);
        } finally {
            Notifikasi::flushEventListeners();
        }
        $this->assertSame('diterima', $application->fresh()->status_lamaran);
        $this->assertNull($proof->fresh()->foto_bukti_bayar);
        $this->assertSame(['old.pdf'], Storage::disk('local')->allFiles());
    }

    public function test_inactive_unverified_and_deleted_sessions_are_denied(): void
    {
        foreach (['pemberi_kerja' => PemberiKerja::class, 'pencari_kerja' => PencariKerja::class] as $role => $model) {
            foreach ([['status_verifikasi' => 'menunggu'], ['status_verifikasi' => 'ditolak'], ['status_akun' => 'nonaktif']] as $state) {
                $account = $model::factory()->create($state);
                $this->post(route('login.process'), ['role' => $role, 'email' => $account->email, 'password' => 'password123'])
                    ->assertSessionHas('error');
                $this->loginAs($role, $account);
                $this->get(route($role === 'pemberi_kerja' ? 'pemberi.dashboard' : 'pencari.dashboard'))->assertRedirect(route('login'));
            }
            $account = $model::factory()->create();
            $this->loginAs($role, $account);
            $account->delete();
            $this->get(route($role === 'pemberi_kerja' ? 'pemberi.dashboard' : 'pencari.dashboard'))->assertRedirect(route('login'));
        }
    }

    public function test_registration_stores_private_identity_and_admin_can_read_it(): void
    {
        $data = $this->accountData(['role' => 'pencari_kerja', 'file_ktp' => UploadedFile::fake()->create('ktp.pdf', 10, 'application/pdf')]);
        $this->post(route('register.process'), $data)->assertRedirect(route('login'));
        $worker = PencariKerja::where('email', $data['email'])->firstOrFail();
        $this->assertSame('menunggu', $worker->status_verifikasi);
        Storage::disk('local')->assertExists($worker->file_ktp);
        Storage::disk('public')->assertMissing($worker->file_ktp);
        $this->loginAs('admin', Admin::factory()->create());
        $this->get(route('admin.akun.ktp', ['pencari_kerja', $worker->id_pencari]))->assertOk();
    }

    public function test_notifikasi_reads_only_current_account_and_marks_its_messages_read(): void
    {
        $one = PencariKerja::factory()->create();
        $two = PencariKerja::factory()->create();
        $own = Notifikasi::kirim($one->id_pencari, 'pencari_kerja', 'Pesan milik satu');
        $other = Notifikasi::kirim($two->id_pencari, 'pencari_kerja', 'Pesan rahasia dua');
        $this->loginAs('pencari_kerja', $one);
        $this->get(route('pencari.notifikasi'))->assertOk()->assertSee('Pesan milik satu')->assertDontSee('Pesan rahasia dua');
        $this->assertEquals(1, $own->fresh()->status_baca);
        $this->assertEquals(0, $other->fresh()->status_baca);
    }

    public function test_job_search_matches_verified_skills_and_radius_on_sqlite(): void
    {
        $worker = PencariKerja::factory()->create(['latitude' => -7.25, 'longitude' => 112.75]);
        $skill = Keahlian::factory()->create();
        KeahlianPencariKerja::factory()->create([
            'id_pencari' => $worker->id_pencari, 'id_keahlian' => $skill->id_keahlian,
            'status_verifikasi_keahlian' => 'terverifikasi',
        ]);
        $near = Pekerjaan::factory()->create(['id_keahlian' => $skill->id_keahlian, 'nama_pekerjaan' => 'Dekat', 'latitude' => -7.251, 'longitude' => 112.751]);
        Pekerjaan::factory()->create(['id_keahlian' => $skill->id_keahlian, 'nama_pekerjaan' => 'Jauh', 'latitude' => -6.2, 'longitude' => 106.8]);
        $this->loginAs('pencari_kerja', $worker);
        $this->get(route('pencari.cari-pekerjaan'))->assertOk()->assertSee('Dekat')->assertDontSee('Jauh');
        $this->get(route('pekerjaan.show', $near))->assertOk();
        $this->get(route('pencari.dashboard'))->assertOk();
        $this->get(route('pencari.profil'))->assertOk();
    }

    public function test_legacy_documents_are_moved_privately_and_command_is_idempotent(): void
    {
        PencariKerja::factory()->create(['file_ktp' => 'ktp/old.pdf']);
        Storage::disk('public')->put('ktp/old.pdf', 'legacy identity');
        $this->artisan('documents:privatize')->assertExitCode(0);
        Storage::disk('public')->assertMissing('ktp/old.pdf');
        $this->assertSame('legacy identity', Storage::disk('local')->get('ktp/old.pdf'));
        $this->artisan('documents:privatize')->assertExitCode(0);
    }

    public function test_legacy_document_collision_preserves_public_source(): void
    {
        PencariKerja::factory()->create(['file_ktp' => 'ktp/collision.pdf']);
        Storage::disk('public')->put('ktp/collision.pdf', 'public version');
        Storage::disk('local')->put('ktp/collision.pdf', 'private version');
        $this->artisan('documents:privatize')->assertExitCode(1);
        $this->assertSame('public version', Storage::disk('public')->get('ktp/collision.pdf'));
        $this->assertSame('private version', Storage::disk('local')->get('ktp/collision.pdf'));
    }

    public function test_quota_update_cannot_drop_accepted_workers_and_can_fill_job(): void
    {
        $job = Pekerjaan::factory()->create(['jumlah_pekerja' => 3]);
        Lamaran::factory()->count(2)->create(['id_pekerjaan' => $job->id_pekerjaan, 'status_lamaran' => 'diterima']);
        $waiting = Lamaran::factory()->create(['id_pekerjaan' => $job->id_pekerjaan]);
        $this->loginAs('pemberi_kerja', $job->pemberiKerja);
        $data = $this->jobData($job->keahlian, ['jumlah_pekerja' => 1]);
        $this->put(route('pemberi.pekerjaan.update', $job), $data)->assertSessionHasErrors('jumlah_pekerja');
        $this->assertSame(3, $job->fresh()->jumlah_pekerja);
        $data['jumlah_pekerja'] = 2;
        $this->put(route('pemberi.pekerjaan.update', $job), $data)->assertSessionHas('success');
        $this->assertSame('penuh', $job->fresh()->status_pekerjaan);
        $this->assertSame('ditolak', $waiting->fresh()->status_lamaran);
    }

    public function test_worker_can_update_own_profile_and_password_without_escalation(): void
    {
        $worker = PencariKerja::factory()->create();
        $this->loginAs('pencari_kerja', $worker);
        $data = $this->accountData(['email' => $worker->email, 'nik' => '1111111111111111', 'id_admin' => 999, 'status_akun' => 'nonaktif']);
        $this->put(route('pencari.profil.update'), $data)->assertSessionHas('success');
        $fresh = $worker->fresh();
        $this->assertSame($worker->nik, $fresh->nik);
        $this->assertSame('aktif', $fresh->status_akun);
        $this->assertNull($fresh->id_admin);
        $this->assertTrue(Hash::check('password123', $fresh->password));
    }

    public function test_failed_profile_update_keeps_original_photo_and_cleans_new_file(): void
    {
        Storage::disk('local')->put('foto_profil/original.jpg', 'original');
        $employer = PemberiKerja::factory()->create(['foto_profil' => 'foto_profil/original.jpg']);
        $this->loginAs('pemberi_kerja', $employer);
        $data = $this->accountData(['email' => $employer->email, 'foto_profil' => UploadedFile::fake()->image('new.png')]);
        PemberiKerja::updating(function (): void {
            throw new \RuntimeException('Database unavailable');
        });
        try {
            $this->put(route('pemberi.profil.update'), $data)->assertStatus(500);
        } finally {
            PemberiKerja::flushEventListeners();
        }
        $this->assertSame('foto_profil/original.jpg', $employer->fresh()->foto_profil);
        $this->assertSame(['foto_profil/original.jpg'], Storage::disk('local')->allFiles('foto_profil'));
        $this->put(route('pemberi.profil.update'), $data)->assertSessionHas('success');
        Storage::disk('local')->assertMissing('foto_profil/original.jpg');
        Storage::disk('local')->assertExists($employer->fresh()->foto_profil);
        $this->get(route('pemberi.profil.foto'))->assertOk();
    }

    public function test_login_honors_role_status_and_does_not_flash_password(): void
    {
        $admin = Admin::factory()->create();
        $this->post(route('login.process'), ['role' => 'admin', 'email' => $admin->email, 'password' => 'password123'])
            ->assertRedirect(route('admin.dashboard'))->assertSessionHas('user_id', $admin->id_admin);
        $this->post(route('logout'))->assertRedirect(route('login'))->assertSessionMissing('login');
        $worker = PencariKerja::factory()->create(['status_akun' => 'nonaktif']);
        $this->post(route('login.process'), ['role' => 'pencari_kerja', 'email' => $worker->email, 'password' => 'password123'])
            ->assertSessionHas('error')->assertSessionMissing('login');
        $this->assertNull(session('_old_input.password'));
    }

    public function test_demo_seeder_creates_skills_and_preserves_existing_progress(): void
    {
        $this->seed(DemoPemberiSeeder::class);
        $this->assertDatabaseCount('keahlian', 1);
        $this->assertDatabaseCount('lamaran', 3);
        $application = Lamaran::firstOrFail();
        $application->update(['status_lamaran' => 'diterima']);
        $this->seed(DemoPemberiSeeder::class);
        $this->assertDatabaseCount('lamaran', 3);
        $this->assertSame('diterima', $application->fresh()->status_lamaran);
    }

    public function test_employer_notifications_show_content_and_only_mark_own_role(): void
    {
        $employer = PemberiKerja::factory()->create();
        $own = Notifikasi::kirim($employer->id_pemberi, 'pemberi_kerja', 'Pelamar baru pengujian');
        $otherRole = Notifikasi::kirim($employer->id_pemberi, 'pencari_kerja', 'Pesan role lain');
        $this->loginAs('pemberi_kerja', $employer);
        $this->get(route('pemberi.notifikasi.index'))->assertOk()->assertSee('Pelamar baru pengujian')->assertDontSee('Pesan role lain');
        $this->assertEquals(1, $own->fresh()->status_baca);
        $this->assertEquals(0, $otherRole->fresh()->status_baca);
    }
}
