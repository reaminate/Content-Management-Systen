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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('categories', 'id')->cascadeOnDelete();
            $table->string('excerpt');
            $table->string('content');
            $table->foreignId('image_id')->constrained('images', 'id')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('authors', 'id')->cascadeOnDelete();
            $table->date('published_at')->default(null)->nullable();
            $table->enum('publication_status', ['draft', 'published', 'archived'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
