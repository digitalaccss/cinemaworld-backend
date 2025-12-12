<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Film extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    protected $table = 'films';

    // data inside these columns that is of json type will be converted into an array when the model is accessed
    protected $casts = [
        'genres' => 'array',
        'countries' => 'array',
        'languages' => 'array'
    ];

    // use the slug database column to search for films and not the id column which is default
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    // each film can have many genres
    // public function genres(){
    //     return $this->belongsToMany(Genre::class, 'film_genre');
    // }

    // each film can have many directors
    public function directors(){
        return $this->belongsToMany(Director::class, 'film_director');
    }

    // each film can have one region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    // each film can have many countries
    // public function countries(){
    //     return $this->belongsToMany(Country::class, 'film_country');
    // }

    // each film can have many languages
    // public function languages(){
    //     return $this->belongsToMany(Language::class, 'film_language');
    // }

    // each film can have many cast
    public function cast(){
        return $this->belongsToMany(Cast::class, 'film_cast');
    }

    // each film can have many tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // each film can be featured once in home
    public function home(){
        return $this->hasOne(Home::class, 'featured_show_id');
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('film_preview_collection');
        $this->addMediaCollection('film_thumbnail_collection')->singleFile();
        $this->addMediaCollection('film_banner_collection')->singleFile();
    }
}
