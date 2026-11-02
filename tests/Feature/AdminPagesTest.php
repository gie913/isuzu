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
        $response->assertSee('Sign in · ' . config('app.name', env('APP_NAME', 'Admin')) . ' Workspace');
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

    public function test_my_profile_page_renders_with_theme(): void
    {
        $user = User::factory()->create([
            'name' => 'Profile User',
            'username' => 'profileuser',
            'email' => 'profile@isuzu.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/my-profile');

        $response->assertStatus(200);
        $response->assertSee('Profile User');
        $response->assertSee('profileuser');
        $response->assertSee('profile@isuzu.com');
        $response->assertSee('myProfileForm');
    }

    public function test_change_password_page_renders_with_theme(): void
    {
        $user = User::factory()->create([
            'name' => 'Password User',
            'username' => 'pwduser',
            'email' => 'pwd@isuzu.com',
            'password' => bcrypt('oldpassword123'),
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/change-password');

        $response->assertStatus(200);
        $response->assertSee('Change Password');
        $response->assertSee('Old Password');
        $response->assertSee('New Password');
        $response->assertSee('New Password Confirmation');
    }

    public function test_user_can_change_password_successfully(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123'),
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put('/admin/change-password', [
            'current_password'      => 'oldpassword123',
            'password'              => 'newSecretPassword!2026',
            'password_confirmation' => 'newSecretPassword!2026',
        ]);

        $response->assertRedirect(route('admin.change-password'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newSecretPassword!2026', $user->password));
    }

    public function test_api_upload_file_and_finalize_on_user_create(): void
    {
        // Seed upload setup for user image_src if not already present
        \App\Models\UploadSetup::firstOrCreate(
            ['m_module_id' => 'user', 'name' => 'image_src'],
            [
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'path' => 'assets/images/user',
                'type' => 'image',
                'allowed_files' => 'png,jpg,jpeg,gif',
                'is_resized' => 1,
                'pic_height' => 100,
                'pic_width' => 100,
                'is_cropped' => 1,
                'ratio_height' => 1,
                'ratio_width' => 1,
                'max_size' => 1000,
                'is_external_storage' => 0,
                'is_active' => true,
            ]
        );

        $file = \Illuminate\Http\UploadedFile::fake()->image('avatar.jpg', 300, 300);

        $uploadResponse = $this->post('/api/v1/upload-file', [
            'file' => $file,
            'm_module_id' => 'user',
            'name' => 'image_src',
        ]);

        $uploadResponse->assertStatus(200);
        $uploadData = $uploadResponse->json('data');
        $this->assertNotNull($uploadData['file_id']);
        $this->assertStringContainsString('tmp_', $uploadData['file_name']);

        // Check t_upload has is_temporary = 1
        $this->assertDatabaseHas('t_upload', [
            'id' => $uploadData['file_id'],
            'is_temporary' => 1,
        ]);

        // Create user with this temporary image_src
        $admin = User::factory()->create(['is_active' => true]);
        $storeResponse = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New Uploaded User',
            'username' => 'newuploaded',
            'email' => 'newuploaded@isuzu.com',
            'password' => 'password12345',
            'image_src' => $uploadData['path'],
        ]);

        $storeResponse->assertRedirect(route('admin.users.index'));

        // Check user has finalized image_src (without tmp_)
        $createdUser = User::where('email', 'newuploaded@isuzu.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertStringNotContainsString('tmp_', $createdUser->image_src);

        // Check t_upload has is_temporary = 0
        $this->assertDatabaseHas('t_upload', [
            'id' => $uploadData['file_id'],
            'is_temporary' => 0,
        ]);
    }
}
