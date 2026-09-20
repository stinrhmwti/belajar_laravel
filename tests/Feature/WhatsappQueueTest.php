<?php

namespace Tests\Feature;

use App\Jobs\KirimPesanWhatsApp;
use App\Models\User;
use App\Models\WhatsappLog;
use App\Services\WhatsappService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WhatsappQueueTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    /**
     * Test pengiriman pesan WA memasukkan data ke status pending dan men-dispatch Job ke antrean.
     */
    public function test_send_whatsapp_creates_pending_log_and_dispatches_job(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->admin)
            ->post('/whatsapp/send', [
                'phone' => '081234567890',
                'message' => 'Tes pesan antrean sistem armada',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('whatsapp_logs', [
            'phone' => '6281234567890',
            'message' => 'Tes pesan antrean sistem armada',
            'status' => WhatsappLog::STATUS_PENDING,
            'attempts' => 0,
        ]);

        $log = WhatsappLog::where('phone', '6281234567890')->first();
        $this->assertNotNull($log);

        Queue::assertPushed(KirimPesanWhatsApp::class, function ($job) use ($log) {
            return $job->whatsappLogId === $log->id;
        });
    }

    /**
     * Test eksekusi Job di latar belakang mengubah status menjadi sent saat respons API sukses.
     */
    public function test_job_execution_updates_status_to_sent_on_success(): void
    {
        Http::fake([
            '*/api/v1/messages/send' => Http::response([
                'success' => true,
                'id' => 'MSG_TEST_9999',
                'message' => 'Pesan terkirim',
            ], 200),
        ]);

        $log = WhatsappLog::create([
            'phone' => '6281234567890',
            'message' => 'Halo driver armada',
            'status' => WhatsappLog::STATUS_PENDING,
            'attempts' => 0,
            'provider' => 'ervelia',
        ]);

        $job = new KirimPesanWhatsApp($log->id);
        $job->handle(app(WhatsappService::class));

        $log->refresh();
        $this->assertEquals(WhatsappLog::STATUS_SENT, $log->status);
        $this->assertEquals(1, $log->attempts);
        $this->assertEquals('MSG_TEST_9999', $log->message_id);
        $this->assertNotNull($log->sent_at);
        $this->assertNull($log->error_message);
    }

    /**
     * Test nomor tidak valid ditandai langsung sebagai failed tanpa masuk antrean.
     */
    public function test_invalid_phone_is_marked_failed_immediately(): void
    {
        Queue::fake();

        $service = app(WhatsappService::class);
        $log = $service->send('123', 'Pesan nomor rusak');

        $this->assertEquals(WhatsappLog::STATUS_FAILED, $log->status);
        $this->assertStringContainsString('tidak valid', $log->error_message);

        Queue::assertNotPushed(KirimPesanWhatsApp::class);
    }

    /**
     * Test fitur kirim ulang manual mereset status ke pending dan mendispatch Job kembali.
     */
    public function test_resend_resets_status_and_dispatches_job(): void
    {
        Queue::fake();

        $log = WhatsappLog::create([
            'phone' => '6281234567890',
            'message' => 'Pesan gagal sebelumnya',
            'status' => WhatsappLog::STATUS_FAILED,
            'attempts' => 5,
            'error_message' => 'Gateway timeout',
            'provider' => 'ervelia',
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/whatsapp/{$log->id}/resend");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $log->refresh();
        $this->assertEquals(WhatsappLog::STATUS_PENDING, $log->status);
        $this->assertEquals(0, $log->attempts);
        $this->assertNull($log->error_message);

        Queue::assertPushed(KirimPesanWhatsApp::class, function ($job) use ($log) {
            return $job->whatsappLogId === $log->id;
        });
    }

    /**
     * Test artisan command whatsapp:retry-stuck mencari pesan pending yang tertahan lebih dari 15 menit.
     */
    public function test_artisan_retry_stuck_dispatches_stuck_jobs(): void
    {
        Queue::fake();

        // Buat log pending yang tertahan 20 menit lalu
        $stuckLog = WhatsappLog::create([
            'phone' => '6281234567890',
            'message' => 'Pesan pending tertahan',
            'status' => WhatsappLog::STATUS_PENDING,
            'attempts' => 1,
            'provider' => 'ervelia',
        ]);
        $stuckLog->created_at = now()->subMinutes(20);
        $stuckLog->saveQuietly();

        // Buat log pending yang baru saja dibuat (5 menit lalu) - tidak boleh di-retry
        $freshLog = WhatsappLog::create([
            'phone' => '6289876543210',
            'message' => 'Pesan pending baru',
            'status' => WhatsappLog::STATUS_PENDING,
            'attempts' => 0,
            'provider' => 'ervelia',
        ]);

        $this->artisan('whatsapp:retry-stuck --minutes=15')
            ->expectsOutputToContain('Ditemukan 1 pesan tertahan')
            ->assertExitCode(0);

        Queue::assertPushed(KirimPesanWhatsApp::class, function ($job) use ($stuckLog) {
            return $job->whatsappLogId === $stuckLog->id;
        });
    }
}
