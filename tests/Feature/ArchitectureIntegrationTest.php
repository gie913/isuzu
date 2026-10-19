<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchitectureIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:keys', ['--force' => true]);
        $this->artisan('passport:client', [
            '--personal' => true,
            '--name' => 'Test Client',
            '--provider' => 'users',
            '--no-interaction' => true,
        ]);
    }

    public function test_locale_query_string_switches_language(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'is_active' => true,
        ]);

        $responseEn = $this->actingAs($user)->get('/admin/dashboard?lang=en');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Dashboard');

        $responseId = $this->actingAs($user)->get('/admin/dashboard?lang=id');
        $responseId->assertStatus(200);
        $responseId->assertSee('Dasbor');
    }

    public function test_passport_login_and_authenticated_api_flow(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret123'),
            'is_active' => true,
        ]);

        // Login using Passport
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token_type',
                    'access_token',
                    'user',
                ],
            ]);

        $token = $loginResponse->json('data.access_token');

        // Access protected profile with token
        $profileResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/profile');

        $profileResponse->assertStatus(200)
            ->assertJsonPath('data.email', 'admin@example.com');

        // Access users api
        $usersResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/users');

        $usersResponse->assertStatus(200)
            ->assertJsonPath('success', true);
    }
}
