<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('t_upload')) {
            Schema::create('t_upload', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('m_upload_setup_id', 36)->nullable();
                $table->string('m_module_id', 36)->nullable();
                $table->string('name')->nullable();
                $table->text('path')->nullable();
                $table->string('type')->nullable();
                $table->text('file_source')->nullable();
                $table->boolean('is_external_storage')->default(false);
                $table->boolean('is_temporary')->default(false);
                $table->boolean('is_active')->default(true);
                $table->uuid('created_by')->nullable();
                $table->uuid('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('t_upload');
    }
};