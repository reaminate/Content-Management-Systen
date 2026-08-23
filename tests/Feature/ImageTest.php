<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use RefreshDatabase;
    //tests whether only valid images can be uploaded
    public function test_valid_image_can_be_uploaded(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Storage::fake('public');

        $file = new UploadedFile(
            base_path('tests/Fixtures/images/jpeg-01-100x100.jpg'),
            'test.jpg',
            'image/jpeg',
            null,
            true,
        );
        $response = $this->postJson('/api/image', [
            'image' => $file,
            'stored_filename'=> 'test',
            'caption' => 'caption',
        ]);
        $response->assertStatus(201);
        $file_gif = new UploadedFile(
            base_path('tests/Fixtures/images/mxj_files-seagull-27431.gif'),
            'test.gif',
            'image/gif',
            null,
            true,
        );
        $response = $this->postJson('/api/image', [
            'image' => $file_gif,
            'stored_filename'=> 'test_false',
            'caption' => 'a caption',
        ]);
        $response->assertStatus(422);
        $this->assertDatabaseHas('images', [
            'stored_filename' => 'test',
            'original_filename' => 'test.jpg',
        ]);
        Storage::disk('public')->assertExists('images/'.$file->hashName());
    }  
    //tests whether the metadata(caption) can be updated without touching the file
    public function test_updating_images_metadata():void
    {
        Sanctum::actingAs(User::factory()->create());

        $image = Image::factory()->create([
            'caption' => 'caption',
        ]);

        $response = $this->patchJson("/api/image/{$image->stored_filename}", [
            'caption' => 'update caption',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'caption' => 'update caption',
            'file_path' => $image->file_path,
        ]);
    }
    public function test_retrieving_images():void
    {
        Sanctum::actingAs(User::factory()->create());

        $images[] = Image::factory()->count(10)->create();

        $response = $this->getJson('/api/image', $images);
        $response->assertStatus(200);
    }
}
