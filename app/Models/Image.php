<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\Models\ImageFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'original_filename',
        'stored_filename',
        'file_path',
        'file_type',
        'filesize',
        'for_author',
        'caption',
        'upload_date',
    ];

    public function author(): HasOne
    {
        return $this->hasOne(Author::class, 'profile_pic');
    }
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'image_id');
    }   
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'content_image');
    }

    protected static function booted(): void
    {
        static::forceDeleted(function (Image $image) {
            Storage::disk('public')->delete($image->file_path);
        });
    }
    public function getRouteKeyName():string
    {
        return 'stored_filename';
    }
}
