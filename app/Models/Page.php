<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\SoftDeletes;
#[Hidden('slug')]
class Page extends Model
{
    /** @use HasFactory<\Database\Factories\Models\PageFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'content_image', 'description', 'publication_status', 'published_date', 'SEO_title', 'SEO_description'];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'content_image');
    }
    public function item(): HasOne
    {
        return $this->hasOne(Item::class);
    }
    protected static function booted(): void
    {
        static::saving(function($model){
            $model->slug = Str::slug($model->title);
        });
    }
    public function getRouteKeyName():string
    {
        return 'slug';
    }
}
