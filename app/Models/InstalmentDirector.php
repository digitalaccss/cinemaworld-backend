<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstalmentDirector extends Model
{
    use HasFactory;

    protected $table = 'instalment_director';

    protected $fillable = ['instalment_id',
        'director_id',
    ];
}
