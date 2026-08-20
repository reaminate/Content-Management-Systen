<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\App\Models\ItemFactory> */
    use HasFactory;
    protected $fillable = ['label', 'url', 'menu_id', 'page_id', 'order'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
    protected static function booted():void
    {
        static::saving(function($model){
            $model->url = Str::slug($model->label);
        });
    }
    public function getRouteKeyName():string
    {
        return 'url';
    }
}
