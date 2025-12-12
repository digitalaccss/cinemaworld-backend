<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstalmentLanguage extends Model
{
    use HasFactory;

    protected $table = 'instalment_language';

    protected $fillable = ['instalment_id',
        'language_id',
    ];
}
