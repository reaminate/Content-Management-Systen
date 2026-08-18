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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('content');
            $table->foreignId('content_image')->constrained('images', 'id')->cascadeOnDelete();
            $table->string('description');
            $table->enum('publication_status', ['draft', 'published', 'archived'])->default('draft');
            $table->date('published_date')->nullable();
            $table->string('SEO_title');
            $table->string('SEO_description');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
