<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('m_user')) {
            Schema::create('m_user', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('code', 20)->nullable();
                $table->string('name');
                $table->string('username', 50)->nullable()->unique();
                $table->string('email')->nullable()->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->uuid('m_role_id')->nullable();
                $table->text('address')->nullable();
                $table->char('gender', 1)->nullable();
                $table->date('birth_date')->nullable();
                $table->string('phone_number', 20)->nullable();
                $table->text('image_src')->nullable();
                $table->timestamp('last_login')->nullable();
                $table->string('login_status', 10)->nullable();
                $table->timestamp('last_connected')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->boolean('set_password')->default(false);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 36)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        if (!Schema::hasTable('m_module')) {
            Schema::create('m_module', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name')->unique();
                $table->string('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('t_module_access')) {
            Schema::create('t_module_access', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('m_role_id');
                $table->uuid('m_module_id');
                $table->boolean('can_create')->default(true);
                $table->boolean('can_read')->default(true);
                $table->boolean('can_update')->default(true);
                $table->boolean('can_delete')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
