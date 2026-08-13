<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Blog_tag extends Pivot
{
    protected $table = 'blog_tags';

    public $timestamps = false;
}
