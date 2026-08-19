<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
#[Hidden('slug')]
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\Models\CategoryFactory> */
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function blog(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    protected static function booted(): void
    {
        static::saving(function ($model){
            $model->slug = Str::slug($model->name);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
