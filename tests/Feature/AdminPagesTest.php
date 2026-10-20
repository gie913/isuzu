<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_with_theme(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Sign in · ISUZU Admin Workspace');
        $response->assertSee('themes/admin/style.76ee5eee.css');
        $response->assertSee('auth-shell');
        $response->assertSee('auth-card');
    }

    public function test_user_can_login_via_web_form(): void
    {
        $user = User::factory()->create([
            'username' => 'isuzuadmin',
            'email' => 'admin@isuzu.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'username' => 'isuzuadmin',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_dashboard_page_renders_with_theme(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@isuzu.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
        $appName = config('app.name', env('APP_NAME', 'ISUZU'));
        $response->assertSee("Dashboard · {$appName} Admin Panel");
        $response->assertSee('themes/admin/2026.5a84696f.js');
        $response->assertSee('d-sidebar');
        $response->assertSee('d-topbar');
        $response->assertSee('d-footer');
        $response->assertSee('hero-title');
    }

    public function test_users_list_page_renders_with_theme_and_datatable(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@isuzu.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('data-table');
        $response->assertSee('data-toolbar');
        $response->assertSee('btn--icon');
        $response->assertSee(route('admin.users.edit', $user->id));
    }

    public function test_users_add_page_renders_with_theme_and_forms(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@isuzu.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/users/add');

        $response->assertStatus(200);
        $response->assertSee('form-grid');
        $response->assertSee('btn--primary');
        $response->assertSee('addUserForm');
    }

    public function test_users_edit_page_renders_with_theme_and_forms(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@isuzu.com',
            'is_active' => true,
        ]);

        $targetUser = User::factory()->create([
            'name' => 'Target User',
            'username' => 'target',
            'email' => 'target@isuzu.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/users/' . $targetUser->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('form-grid');
        $response->assertSee('editUserForm');
        $response->assertSee('Target User');
    }

    public function test_users_show_page_renders_with_theme(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@isuzu.com',
            'is_active' => true,
        ]);

        $targetUser = User::factory()->create([
            'name' => 'Details User',
            'username' => 'detailuser',
            'email' => 'detail@isuzu.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/users/' . $targetUser->id);

        $response->assertStatus(200);
        $response->assertSee('form-grid');
        $response->assertSee('Details User');
        $response->assertSee('detailuser');
    }

    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@isuzu.com',
            'is_active' => true,
        ]);

        $targetUser = User::factory()->create([
            'name' => 'Delete Me',
            'username' => 'deleteme',
            'email' => 'delete@isuzu.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete('/admin/users/' . $targetUser->id);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('m_user', [
            'id' => $targetUser->id,
            'is_active' => 0,
        ]);
    }

    public function test_users_list_ajax_page_renders(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@isuzu.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/users/list');

        $response->assertStatus(200);
        $response->assertSee('ajaxUsersTable');
        $response->assertSee('ajaxSearchInput');
        $response->assertSee('admin/users/list/data', false);
    }

    public function test_users_list_ajax_data_returns_paginated_json(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@isuzu.com',
            'is_active' => true,
        ]);

        User::factory()->count(5)->create(['is_active' => true]);

        $response = $this->actingAs($user)->getJson('/admin/users/list/data?per_page=5&page=1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'short_id',
                    'name',
                    'username',
                    'email',
                    'is_active',
                    'status_html',
                    'initials',
                    'avatar_class',
                    'detail_url',
                    'created_at',
                    'actions',
                ]
            ],
            'current_page',
            'last_page',
            'per_page',
            'total',
        ]);
    }
}
