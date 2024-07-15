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

    // each campaign can have 1 carousel
    public function carousel(){
        return $this->belongsTo(Carousel::class);
    }
}
