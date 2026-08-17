<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Blog_tag extends Pivot
{
    use HasFactory;

    protected $table = 'blog_tags';

    public $timestamps = false;
}
