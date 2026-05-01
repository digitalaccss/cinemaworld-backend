<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns';

    protected $casts = [
        'content' => 'json',
    ];

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

    // each campaign can have 1 carousel
    public function carousel(){
        return $this->belongsTo(Carousel::class);
    }
}
