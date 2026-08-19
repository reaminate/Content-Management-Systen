<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\App\Models\MenuFactory> */
    use HasFactory;
    protected $fillable = ['name', 'identifier', 'description', 'active_status'];
    
    public function items(): HasMany
    {
        return $this->hasMany(Item::class)->orderBy('order');
    }
    protected static function booted():void{
        static::saving(function($model){
            $model->identifier = Str::slug($model->name);
        });
    }
}
