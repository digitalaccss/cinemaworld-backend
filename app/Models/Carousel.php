<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;

    protected $table = 'carousels';

    // protected $fillable = ['tag_id'];

    protected $casts = [
        'shows' => 'array'
    ];

    // each carousel can belong to 1 carousel type
    // public function carouselType(){
    //     return $this->belongsTo(CarouselType::class);
    // }

    public function campaign(){
        return $this->hasOne(Campaign::class, 'carousel_id');
    }

    // active status can have many tags that are assigned to it
    // public function tag(){
    //     return $this->belongsTo(Tag::class);
    // }
}
