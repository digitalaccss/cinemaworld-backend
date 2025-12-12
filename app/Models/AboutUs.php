<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AboutUs extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    protected $table = 'aboutus';

    protected $casts = [
        'content' => 'json',
        //'mobile_content' => 'json',
    ];
}
