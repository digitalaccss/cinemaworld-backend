<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Cast extends Model
{
    use HasFactory;

    use Searchable;

    // use InteractsWithMedia;

    protected $table = 'cast';

    // each cast can have many shows
    public function shows(){
        return $this->belongsToMany(Show::class, 'show_cast');
    }

    public function instalments(){
        return $this->belongsToMany(Instalment::class, 'instalment_cast');
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'cast_index';
    }

    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this
    //         ->addMediaConversion('preview')
    //         ->fit(Manipulations::FIT_CROP, 300, 300)
    //         ->nonQueued();
    // }

    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(130)
    //         ->height(130);
    // }

    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('cast-collection')->singleFile();
    // }
}
