<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critic extends Model
{
    use HasFactory;

    protected $table = 'critics';

    protected $fillable = [
        'show_id',
        'name',
        'critique'
    ];

    // each critic can have 1 show
    public function show(){
        return $this->belongsTo(Show::class);
    }
}
