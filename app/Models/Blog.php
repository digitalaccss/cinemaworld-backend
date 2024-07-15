<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\BelongsTo;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $casts = [
        'content' => 'json',
        'blog_type' => 'array',
        // 'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function blogType()
    {
        //return $this->belongsTo(blogType::class);
        return $this->belongsTo(BlogType::class);
    }
}


