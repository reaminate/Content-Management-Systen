<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
#[Hidden('slug')]
class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\Models\BlogFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title',  'excerpt', 'content', 'category_id', 'author_id', 'image_id', 'publication_status', 'published_at'];
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_tags')->using(Blog_tag::class);
    }
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected static function booted(): void
    {
        static::saving(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
