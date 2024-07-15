<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $table = 'genres';

    public function setGenreDisplayNameAttribute($value)
    {
       $this->attributes['genre_display_name'] = $value;
       
       $this->setGenreNameAttribute($value);
    }

    public function setGenreNameAttribute($genreDisplayNameVal){
        $this->attributes['genre_name'] = str_replace([' ', '&'], '', $genreDisplayNameVal);
    }

    // each genre can have many shows
    public function films(){
        return $this->belongsToMany(Show::class, 'show_genre');
    }
}
