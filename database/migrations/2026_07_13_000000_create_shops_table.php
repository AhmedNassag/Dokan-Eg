<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {

            $table->id();

            // Owner
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Basic Information
            $table->string('name', 150);
            $table->string('slug', 180)->unique();

            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('cover', 255)->nullable();

            // Branding
            $table->string('theme', 50)->default('default');
            $table->string('primary_color', 20)->default('#000000');
            $table->string('secondary_color', 20)->default('#ffffff');
            $table->string('font_family', 50)->default('Cairo');

            // Contact
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 255)->nullable();

            // Social Media
            $table->json('social_links')->nullable();

            // SEO
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->unique('user_id');

            $table->index('slug');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('published_at');

            $table->index([
                'user_id',
                'is_active'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
