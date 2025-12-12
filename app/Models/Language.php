<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

    public function setLanguageDisplayNameAttribute($value)
    {
       $this->attributes['language_display_name'] = $value;
       
       $this->setLanguageNameAttribute($value);
    }

    public function setLanguageNameAttribute($languageDisplayNameVal){
        $this->attributes['language_name'] = str_replace([' ', '&'], '', $languageDisplayNameVal);
    }

    // each language can have many shows
    public function shows(){
        return $this->belongsToMany(Show::class, 'show_language');
    }

    public function instalments(){
        return $this->belongsToMany(Instalment::class, 'instalment_language');
    }
}
