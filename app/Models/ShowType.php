<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Laravel\Scout\Searchable;

class ShowType extends Model
{
    use HasFactory;

    // use Searchable;

    protected $table = 'show_types';

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    // public function searchableAs()
    // {
    //     return 'show_type_index';
    // }

    // each show type can only have 1 home
    // public function home(){
    //     return $this->hasOne(Home::class, 'show_type_id');
    // }

    // each show type can have 1 show
    public function show(){
        return $this->hasOne(Show::class, 'show_type_id');
    }
}
