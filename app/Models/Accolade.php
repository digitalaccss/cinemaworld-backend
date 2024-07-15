<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Accolade extends Model
{
    use HasFactory;

    // use InteractsWithMedia;

    protected $table = 'accolades';

    protected $fillable = [
        'name',
        'category',
    ];

    // each accolade can have many shows
    public function shows(){
        return $this->belongsToMany(Show::class, 'show_accolade');
    }

    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('accolade-cover-collection')->singleFile();
    // }
}
