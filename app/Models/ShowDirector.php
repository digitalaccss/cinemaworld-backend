<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowDirector extends Model
{
    use HasFactory;

    protected $table = 'show_director';

    protected $fillable = ['show_id',
        'director_id',
    ];
}
