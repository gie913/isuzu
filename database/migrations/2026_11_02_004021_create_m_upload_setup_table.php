<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('m_upload_setup')) {
            Schema::create('m_upload_setup', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('m_module_id', 36)->nullable();
                $table->string('name')->nullable();
                $table->text('path')->nullable();
                $table->string('type')->nullable();
                $table->string('allowed_files')->nullable();
                $table->boolean('is_resized')->default(false);
                $table->integer('pic_height')->default(0);
                $table->integer('pic_width')->default(0);
                $table->boolean('is_cropped')->default(false);
                $table->integer('ratio_height')->default(0);
                $table->integer('ratio_width')->default(0);
                $table->boolean('using_thumb')->default(false);
                $table->integer('thumb_height')->default(0);
                $table->integer('thumb_width')->default(0);
                $table->integer('max_size')->default(2048);
                $table->boolean('is_external_storage')->default(false);
                $table->boolean('is_active')->default(true);
                $table->uuid('created_by')->nullable();
                $table->uuid('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('m_upload_setup');
    }
};