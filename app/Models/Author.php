<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
#[Hidden('slug')]
class Author extends Model
{
    /** @use HasFactory<\Database\Factories\Models\AuthorFactory> */
    use HasFactory;
    protected $fillable = ['name', 'email', 'short_biography', 'profile_pic'];

    public function blog(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'profile_pic');
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
