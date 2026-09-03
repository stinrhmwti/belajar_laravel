<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_locale_switching_logic()
    {
        // 1. Test set-locale route for 'en'
        $response = $this->get('/set-locale/en');
        $response->assertRedirect();
        $this->assertEquals('en', session('locale'));

        // 2. Test set-locale route for 'id'
        $response = $this->get('/set-locale/id');
        $response->assertRedirect();
        $this->assertEquals('id', session('locale'));
    }

    public function test_login_page_localization()
    {
        // Test Login in English
        $response = $this->withSession(['locale' => 'en'])->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Log in to Fleet System');
        $response->assertSee('Email or Username');

        // Test Login in Indonesian
        $response = $this->withSession(['locale' => 'id'])->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk Sistem Armada');
        $response->assertSee('Email atau Username');
    }

    public function test_dashboard_localization_for_admin()
    {
        $admin = User::where('role', 'admin')->first();

        // Test Dashboard in English
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Main Menu');
        $response->assertSee('KIR Overdue');

        // Test Dashboard in Indonesian
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Menu Utama');
        $response->assertSee('KIR Lewat Tempo');
    }

    public function test_vehicles_page_localization()
    {
        $admin = User::where('role', 'admin')->first();

        // Test Vehicles in English
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get('/vehicles');
        $response->assertStatus(200);
        $response->assertSee('Vehicle & Service Data');
        $response->assertSee('Total Fleet');

        // Test Vehicles in Indonesian
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/vehicles');
        $response->assertStatus(200);
        $response->assertSee('Data Kendaraan & Servis');
        $response->assertSee('Total Armada');
    }

    public function test_expenses_page_localization()
    {
        $admin = User::where('role', 'admin')->first();

        // Test Expenses in English
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get('/expenses');
        $response->assertStatus(200);
        $response->assertSee('Operational Expenses Summary');
        $response->assertSee('Expense Category');

        // Test Expenses in Indonesian
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/expenses');
        $response->assertStatus(200);
        $response->assertSee('Rekap Biaya Operasional');
        $response->assertSee('Kategori Biaya');
    }

    public function test_complaints_page_localization()
    {
        $admin = User::where('role', 'admin')->first();

        // Test Complaints in English
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get('/complaints');
        $response->assertStatus(200);
        $response->assertSee('List of Complaints & Vehicle Issues');

        // Test Complaints in Indonesian
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/complaints');
        $response->assertStatus(200);
        $response->assertSee('Daftar Keluhan & Masalah Kendaraan');
    }
}
