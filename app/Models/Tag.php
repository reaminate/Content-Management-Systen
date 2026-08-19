<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Hidden('slug')]
class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\Models\TagFactory> */
    use HasFactory;

    protected $fillable = ['name'];
    public function blogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class, 'blog_tags')->using(Blog_tag::class);
    }

    protected static function booted():void
    {
        static::saving(function ($model){
            $model->slug = Str::slug($model->name);
        });
    }
}
