<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user
        $this->adminUser = User::factory()->create([
            'user_type' => 'admin',
        ]);
    }

    public function test_admin_can_access_general_settings_page()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.settings.general'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.general');
    }

    public function test_admin_can_update_general_settings()
    {
        $data = [
            'site_name' => 'Test Site Name',
            'contact_email' => 'test@example.com',
            'default_profit_margin' => '15.00',
            'auto_send_invoice' => '1',
        ];

        $response = $this->actingAs($this->adminUser)
                         ->put(route('admin.settings.general.update'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('system_settings', [
            'group' => 'general',
            'key' => 'site_name',
            'value' => 'Test Site Name',
        ]);

        $this->assertDatabaseHas('system_settings', [
            'group' => 'general',
            'key' => 'default_profit_margin',
            'value' => '15.00',
        ]);
        
        $this->assertDatabaseHas('system_settings', [
            'group' => 'general',
            'key' => 'auto_send_invoice',
            'value' => '1',
        ]);
    }

    public function test_admin_can_access_smtp_settings_page()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.settings.smtp'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.smtp');
    }

    public function test_admin_can_update_smtp_settings()
    {
        $data = [
            'mail_host' => 'smtp.test.com',
            'mail_port' => '2525',
        ];

        $response = $this->actingAs($this->adminUser)
                         ->put(route('admin.settings.smtp.update'), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('system_settings', [
            'group' => 'smtp',
            'key' => 'mail_host',
            'value' => 'smtp.test.com',
        ]);
    }

    public function test_admin_can_access_seo_settings_page()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.settings.seo'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.seo');
    }

    public function test_admin_can_update_seo_settings()
    {
        $data = [
            'meta_title' => 'Test Meta Title',
        ];

        $response = $this->actingAs($this->adminUser)
                         ->put(route('admin.settings.seo.update'), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('system_settings', [
            'group' => 'seo',
            'key' => 'meta_title',
            'value' => 'Test Meta Title',
        ]);
    }

    public function test_admin_can_access_third_party_settings_page()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.settings.third-party'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.third-party');
    }

    public function test_admin_can_update_third_party_settings()
    {
        $data = [
            'google_analytics_id' => 'UA-123456-7',
        ];

        $response = $this->actingAs($this->adminUser)
                         ->put(route('admin.settings.third-party.update'), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('system_settings', [
            'group' => 'third_party',
            'key' => 'google_analytics_id',
            'value' => 'UA-123456-7',
        ]);
    }
}
