<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\DailyChecklist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyChecklistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_driver_can_access_checklist_pages()
    {
        // Ambil data user dengan role driver (user)
        $driver = User::where('role', 'user')->first();

        // 1. Verifikasi driver bisa melihat daftar checklist
        $response = $this->actingAs($driver)->get('/checklist');
        $response->assertStatus(200);

        // 2. Verifikasi driver bisa melihat halaman buat checklist
        $response = $this->actingAs($driver)->get('/checklist-create');
        $response->assertStatus(200);
    }

    public function test_driver_checklist_filtering_and_validation()
    {
        $driver = User::where('role', 'user')->first();
        
        // Buat kendaraan yang ditugaskan untuk driver ini
        $assignedVehicle = Vehicle::create([
            'jenis_kendaraan' => 'Mobil',
            'merek' => 'Toyota',
            'tipe' => 'Avanza',
            'tahun' => 2020,
            'plat_nomor' => 'B 1234 SBY',
            'supir_utama' => $driver->name,
            'odometer_awal' => 10000,
            'status' => 'Siap Pakai',
        ]);

        // Buat kendaraan lain yang ditugaskan ke driver lain
        $otherVehicle = Vehicle::create([
            'jenis_kendaraan' => 'Mobil',
            'merek' => 'Mitsubishi',
            'tipe' => 'Xpander',
            'tahun' => 2021,
            'plat_nomor' => 'B 5678 JKT',
            'supir_utama' => 'Supir Lain',
            'odometer_awal' => 20000,
            'status' => 'Siap Pakai',
        ]);

        // Uji bahwa halaman create hanya memunculkan plat nomor kendaraan penugasan driver tersebut
        $response = $this->actingAs($driver)->get('/checklist-create');
        $response->assertSee($assignedVehicle->plat_nomor);
        $response->assertDontSee($otherVehicle->plat_nomor);

        // Uji driver mencoba mengirim checklist untuk kendaraan lain yang bukan miliknya (harus gagal)
        $response = $this->actingAs($driver)->post('/checklist', [
            'vehicle_id' => $otherVehicle->id,
            'tanggal' => now()->toDateString(),
            'nama_teknisi' => $driver->name,
            'odometer' => 20500,
            'oli_mesin' => 'OK',
            'air_radiator' => 'OK',
            'minyak_rem' => 'OK',
            'ban_rem' => 'OK',
            'lampu_klakson' => 'OK',
            'kebersihan' => 'OK',
        ]);
        $response->assertSessionHasErrors('vehicle_id');

        // Uji driver mengirim checklist untuk kendaraan miliknya sendiri (harus berhasil)
        $response = $this->actingAs($driver)->post('/checklist', [
            'vehicle_id' => $assignedVehicle->id,
            'tanggal' => now()->toDateString(),
            'nama_teknisi' => $driver->name,
            'odometer' => 10500,
            'oli_mesin' => 'OK',
            'air_radiator' => 'OK',
            'minyak_rem' => 'OK',
            'ban_rem' => 'OK',
            'lampu_klakson' => 'OK',
            'kebersihan' => 'OK',
        ]);
        $response->assertRedirect('/checklist');
        $response->assertSessionHasNoErrors();

        // Verifikasi odometer kendaraan terbarui
        $assignedVehicle->refresh();
        $this->assertEquals(10500, $assignedVehicle->odometer_awal);
    }

    public function test_driver_cannot_delete_checklist()
    {
        $driver = User::where('role', 'user')->first();
        $admin = User::where('role', 'admin')->first();

        $vehicle = Vehicle::create([
            'jenis_kendaraan' => 'Mobil',
            'merek' => 'Toyota',
            'tipe' => 'Avanza',
            'tahun' => 2020,
            'plat_nomor' => 'B 9999 DET',
            'supir_utama' => $driver->name,
            'odometer_awal' => 10000,
            'status' => 'Siap Pakai',
        ]);

        $checklist = DailyChecklist::create([
            'vehicle_id' => $vehicle->id,
            'tanggal' => now()->toDateString(),
            'nama_teknisi' => $driver->name,
            'odometer' => 11000,
            'oli_mesin' => 'OK',
            'air_radiator' => 'OK',
            'minyak_rem' => 'OK',
            'ban_rem' => 'OK',
            'lampu_klakson' => 'OK',
            'kebersihan' => 'OK',
        ]);

        // Coba hapus checklist sebagai driver (harus gagal)
        $response = $this->actingAs($driver)->delete('/checklist/' . $checklist->id);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('daily_checklists', ['id' => $checklist->id]);

        // Coba hapus checklist sebagai admin (harus sukses)
        $response = $this->actingAs($admin)->delete('/checklist/' . $checklist->id);
        $response->assertRedirect('/checklist');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('daily_checklists', ['id' => $checklist->id]);
    }
}
