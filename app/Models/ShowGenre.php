<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowGenre extends Model
{
    use HasFactory;

    protected $table = 'show_genre';

    protected $fillable = ['show_id',
        'genre_id',
    ];
}
