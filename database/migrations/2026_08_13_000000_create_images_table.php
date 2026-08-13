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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename');
            $table->string('stored_filename')->unique();
            $table->longText('file_path');
            $table->enum('file_type', ['image/jpeg', 'image/png', 'image/svg']);
            $table->integer('filesize');
            $table->string('caption');
            $table->string('image_for');
            $table->date('upload_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
