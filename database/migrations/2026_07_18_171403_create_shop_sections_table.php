<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50);
            $table->string('title', 255)->nullable();
            $table->json('content')->nullable();
            $table->json('settings')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['shop_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_sections');
    }
};
