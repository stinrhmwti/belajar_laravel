<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Database\Seeders\UserSeeder;
use Database\Seeders\VehicleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(VehicleSeeder::class);
    }

    public function test_guest_is_redirected_from_tracking_page(): void
    {
        $response = $this->get('/tracking');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_tracking_page(): void
    {
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/tracking');
        $response->assertStatus(200);
        $response->assertSee('Pelacakan Kendaraan');
        $response->assertSee('liveFleetMap');
    }

    public function test_tracking_api_returns_valid_vehicles_json(): void
    {
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);

        // Buat data uji kendaraan
        $vehicle = Vehicle::create([
            'jenis_kendaraan' => 'Mobil Boks',
            'merek' => 'Isuzu',
            'tipe' => 'Elf NMR 71',
            'tahun' => 2022,
            'plat_nomor' => 'B 9999 TRK',
            'supir_utama' => 'Budi Santoso',
            'lokasi_pool' => 'Pool Jakarta Pusat',
            'odometer_awal' => 15000,
            'status' => 'Siap Pakai',
            'latitude' => -6.208763,
            'longitude' => 106.845599,
        ]);

        $response = $this->actingAs($user)->getJson('/tracking/api/vehicles');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'total',
            'vehicles' => [
                '*' => [
                    'id',
                    'plat_nomor',
                    'merek',
                    'tipe',
                    'status',
                    'supir_utama',
                    'lokasi_pool',
                    'odometer',
                    'latitude',
                    'longitude',
                    'foto_url',
                    'marker_type',
                    'icon_class',
                    'detail_url',
                ]
            ]
        ]);
        $response->assertSee('B 9999 TRK');
    }

    public function test_user_can_update_vehicle_gps_location(): void
    {
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);

        $vehicle = Vehicle::create([
            'jenis_kendaraan' => 'Motor',
            'merek' => 'Honda',
            'tipe' => 'Vario 160',
            'tahun' => 2023,
            'plat_nomor' => 'B 8888 MTR',
            'supir_utama' => 'Kurir Express',
            'lokasi_pool' => 'Pool Palmerah',
            'odometer_awal' => 5000,
            'status' => 'Siap Pakai',
            'latitude' => -6.201720,
            'longitude' => 106.782155,
        ]);

        $newLat = -6.175392;
        $newLng = 106.827153;
        $newPool = 'Pool Monas Pusat';

        $response = $this->actingAs($user)->postJson("/tracking/{$vehicle->id}/location", [
            'latitude' => $newLat,
            'longitude' => $newLng,
            'lokasi_pool' => $newPool,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'latitude' => $newLat,
            'longitude' => $newLng,
            'lokasi_pool' => $newPool,
        ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'latitude' => $newLat,
            'longitude' => $newLng,
            'lokasi_pool' => $newPool,
        ]);
    }

    public function test_invalid_coordinates_are_rejected(): void
    {
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);

        $vehicle = Vehicle::create([
            'jenis_kendaraan' => 'Mobil',
            'merek' => 'Toyota',
            'tipe' => 'Avanza',
            'tahun' => 2021,
            'plat_nomor' => 'B 7777 AVZ',
            'supir_utama' => 'Driver Uji',
            'lokasi_pool' => 'Pool Cawang',
            'odometer_awal' => 12000,
            'status' => 'Siap Pakai',
        ]);

        // Latitude > 90 harus gagal validasi
        $response = $this->actingAs($user)->postJson("/tracking/{$vehicle->id}/location", [
            'latitude' => 150.000, // Invalid latitude
            'longitude' => 106.827153,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['latitude']);
    }
}
