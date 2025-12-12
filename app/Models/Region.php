<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    public function setRegionDisplayNameAttribute($value)
    {
       $this->attributes['region_display_name'] = $value;
       
       $this->setRegionNameAttribute($value);
    }

    public function setRegionNameAttribute($regionDisplayNameVal){
        $this->attributes['region_name'] = str_replace([' ', '&'], '', $regionDisplayNameVal);
    }

    // each region belongs to 1 film
    public function show()
    {
        // looking for region id column in film table
        return $this->hasOne(Show::class, 'region_id');
    }
}
