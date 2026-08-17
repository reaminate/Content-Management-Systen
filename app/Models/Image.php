<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\Models\ImageFactory> */
    use HasFactory;
    protected $fillable = ['stored_filename', 'caption', 'file_path', 'upload_date'];

    public function authors(): HasMany
    {
        return $this->hasMany(Author::class, 'profile_pic');
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
        static::creating(function ($model) {
            $model->original_filename = basename($model->file_path);
            $model->file_type = mime_content_type($model->file_path);
            $model->filesize = filesize($model->file_path);
        });
    }
}
