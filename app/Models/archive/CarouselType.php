<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselType extends Model
{
    use HasFactory;

    protected $table = 'carousel_types';

    // each carousel type can have 1 show
    public function carousel(){
        return $this->hasOne(Carousel::class, 'carousel_type_id');
    }
}
