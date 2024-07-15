<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowAccolade extends Model
{
    use HasFactory;

    protected $table = 'show_accolade';

    protected $fillable = [
        'show_id',
        'accolade_id',
    ];
}
