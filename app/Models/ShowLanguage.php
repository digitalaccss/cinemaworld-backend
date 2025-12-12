<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowLanguage extends Model
{
    use HasFactory;

    protected $table = 'show_language';

    protected $fillable = ['show_id',
        'language_id',
    ];
}
