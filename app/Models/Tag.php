<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    public function setTagDisplayNameAttribute($value)
    {
       $this->attributes['tag_display_name'] = $value;
       
       $this->setTagNameAttribute($value);
    }

    public function setTagNameAttribute($tagDisplayNameVal){
        $this->attributes['tag_name'] = str_replace([' ', '&'], '', $tagDisplayNameVal);
    }

    // each tag can have multiple shows that are films
    public function shows()
    {
        return $this->belongsToMany(Show::class);
    }

    // each tag can only have the active status assigned to it at one time
    public function activeTags(){
        return $this->hasMany(ActiveTag::class, 'tag_id');
    }
}
