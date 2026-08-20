<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\App\Models\SettingFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'email', 'phone', 'address', 'facebook', 'linkedin', 'instagram', 'SEO_title', 'SEO_description'];
}
