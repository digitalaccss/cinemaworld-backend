<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    public function setCountryDisplayNameAttribute($value)
    {
       $this->attributes['country_display_name'] = $value;
       
       $this->setCountryNameAttribute($value);
    }

    public function setCountryNameAttribute($countryDisplayNameVal){
        $this->attributes['country_name'] = str_replace([' ', '&'], '', $countryDisplayNameVal);
    }

    // each country can have many films
    public function shows(){
        return $this->belongsToMany(Show::class, 'show_country');
    }
}
