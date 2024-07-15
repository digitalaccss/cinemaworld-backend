<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Home extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    protected $table = 'home';

    protected $casts = [
        'active_carousels' => 'array',
        //'customize_banner_display_text' => 'json',
        //'customize_banner_button_text' => 'json'
    ];

    // home can have one featured show
    public function featuredShow(){
        return $this->belongsTo(Show::class);
    }

    // home can have one show type
    // public function showType(){
    //     return $this->belongsTo(ShowType::class);
    // }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured-banner-collection')->singleFile();
    }
}
