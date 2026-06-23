<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Director extends Model
{
    use HasFactory;

    use Searchable;

    // use InteractsWithMedia;

    protected $table = 'directors';

    /**
     * Set the slug attribute to always be lowercase
     *
     * @param  string  $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = strtolower($value);
    }

    /**
     * Get the effective meta title (manual or auto-generated).
     *
     * @return string
     */
    public function getEffectiveMetaTitleAttribute()
    {
        if (!empty($this->meta_title)) {
            return $this->meta_title;
        }

        return $this->name . ' | CinemaWorld';
    }

    /**
     * Get the effective meta description (manual or auto-generated).
     *
     * @return string
     */
    public function getEffectiveMetaDescriptionAttribute()
    {
        if (!empty($this->meta_description)) {
            return $this->meta_description;
        }

        if (!empty($this->description)) {
            return \Illuminate\Support\Str::limit(strip_tags($this->description), 155);
        }

        return 'Discover films and shows directed by ' . $this->name . ' on CinemaWorld.';
    }

    // each director can have many shows
    public function shows(){
        return $this->belongsToMany(Show::class, 'show_director');
    }

    public function instalments(){
        return $this->belongsToMany(Instalment::class, 'instalment_director');
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'directors_index';
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
    //     $this->addMediaCollection('director-collection')->singleFile();
    // }
}
