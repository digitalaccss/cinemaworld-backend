<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PartnershipEvent extends Model
{
    use HasFactory;

    // use InteractsWithMedia;

    protected $table = 'partnership_events';

    protected $casts = [
        'content' => 'json',
        // 'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    // public function registerMediaCollections(): void
    // {
        // partnerships
    //     $this->addMediaCollection('partnership-cover-collection')->singleFile();
    //     $this->addMediaCollection('partnership-banner-collection')->singleFile();

        // events
    //     $this->addMediaCollection('event-cover-collection')->singleFile();
    //     $this->addMediaCollection('event-banner-collection')->singleFile();
    // }
}
