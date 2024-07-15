<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Show extends Model implements HasMedia
{
    use HasFactory;

    use Searchable;

    use InteractsWithMedia;

    protected $table = 'shows';

    /**
     * Interact with the show's accolades.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    // protected function accolades(): Attribute
    // {
    //     return Attribute::make(
    //         // get the value when it is accessed from the model' column attribute
    //         // get: fn ($value) => ucfirst($value),
    //         get: fn ($value) => $value,
    //         // before the value is saved to the column attribute on the model
    //         // set: fn ($value) => strtolower($value),
    //         set: fn ($value) => $this->test($value),
    //     );
    // }

    // data inside these columns that is of json type will be converted into an array when the model is accessed
    protected $casts = [
        'genres' => 'array',
        'countries' => 'array',
        'languages' => 'array',
        'director_statement' => 'json',
        'expiry_date' => 'date'
    ];

    protected $fillable = ['house_media_id',
    'title',
    'slug',
    'foreign_title',
    'show_type_id',
    'expiry_date',
    'runtime',
    'full_desc',
    'release_year',
    'region_id',
    'trailer_link_url',
    'short_desc',
    'trivia_desc',
    'director_statement',
    'cover_photo_path',
    'banner_path',
    'director_photo_path',
    'leader_board_banner_path',
    'expiry_date',
    'is_publish',
    'is_new',
    'on_demand',
    'sub_title'
];

    // public function test($value){
    //     $test = [];
    //     foreach($value as $v){
    //         $test['accolades']['attributes'] = $v['attributes'];
    //     }
    //     return $test;
    // }

    // each show can belong to 1 show type
    // public function showType(){
    //     return $this->belongsTo(ShowType::class);
    // }

    // each series can have many instalments
    public function instalments(){
        return $this->hasMany(Instalment::class, 'series_id');
    }

    // each show can have many directors
    public function directors(){
        return $this->belongsToMany(Director::class, 'show_director');
    }

    // each show can have one region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    // each show can have many genres
    public function genres(){
        return $this->belongsToMany(Genre::class, 'show_genre');
    }

    // each show can have many countries
    public function countries(){
        return $this->belongsToMany(Country::class, 'show_country');
    }

    // each show can have many languages
    public function languages(){
        return $this->belongsToMany(Language::class, 'show_language');
    }

    // each show can have many accolades
    public function accolades(){
        return $this->belongsToMany(Accolade::class, 'show_accolade');
    }

    // each show can have many critics
    public function critics(){
        return $this->hasMany(Critic::class, 'show_id');
    }

    // each show can have many cast
    public function cast(){
        return $this->belongsToMany(Cast::class, 'show_cast');
    }

    // each show can have many schedules
    public function schedules(){
        return $this->hasMany(Schedule::class, 'show_id');
    }

    // each show can have many tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // each show can be featured once in home
    public function home(){
        return $this->hasOne(Home::class, 'featured_show_id');
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'shows_index';
    }

    // define searchable columns of model (not working)
    // public function toSearchableArray()
    // {
    //     $array = $this->toArray();
 
    //     $data = [
    //         'id' => $array['id'],
    //         'title' => $array['title'],
    //         'slug' => $array['slug'],
    //         'show_type_id' => $array['show_type_id'],
    //         'trailer_link_url' => $array['trailer_link_url'],
    //     ];
 
    //     return $data;
    // }
    
    public function registerMediaCollections(): void
    {
        // films
        // $this->addMediaCollection('film-cover-collection')->singleFile();
        // $this->addMediaCollection('film-banner-collection')->singleFile();
        $this->addMediaCollection('film-gallery-collection');

        // shorts
        // $this->addMediaCollection('short-cover-collection')->singleFile();
        // $this->addMediaCollection('short-banner-collection')->singleFile();
        $this->addMediaCollection('short-gallery-collection');

        // series
        // $this->addMediaCollection('series-cover-collection')->singleFile();
        // $this->addMediaCollection('series-banner-collection')->singleFile();
        $this->addMediaCollection('series-gallery-collection');

        // docufilm
        $this->addMediaCollection('docufilm-gallery-collection');

        // director statement
        // $this->addMediaCollection('director-statement-collection')->singleFile();
    }
}
