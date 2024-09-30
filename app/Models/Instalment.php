<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Chelout\RelationshipEvents\Concerns\HasManyEvents;

use Laravel\Scout\Searchable;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Instalment extends Model implements HasMedia
{
    use HasFactory;

    use HasManyEvents;

    use Searchable;

    use InteractsWithMedia;

    protected $table = 'instalments';

    protected $casts = [
        'expiry_date' => 'date'
    ];

    protected $fillable = ['house_media_id',
    'title',
    'slug',
    'foreign_title',
    'expiry_date',
    'runtime',
    'full_desc',
    'release_year',
    'trailer_link_url',
    'short_desc',
    'trivia_desc',
    'director_statement',
    'cover_photo_path',
    'banner_path',
    'director_photo_path',
    'leader_board_banner_path',
    'expiry_date',
    'series_id',
    'instalment_number',
    'is_publish',
    'is_new',
    'on_demand',
    'sub_title',
    'meta_title',
    'meta_description'
];

    // each instalment can have 1 series
    public function series(){
        return $this->belongsTo(Show::class);
    }

    // each instalment can have many languages
    public function languages(){
        return $this->belongsToMany(Language::class, 'instalment_language');
    }

    // each instalment can have many schedules
    public function schedules(){
        return $this->hasMany(Schedule::class, 'show_id', 'series_id')->where('instalment_id', $this->id);
    }

    // each instalment can have many cast
    public function cast(){
        return $this->belongsToMany(Cast::class, 'instalment_cast');
    }

    // each instalment can have many directors
    public function directors(){
        return $this->belongsToMany(Director::class, 'instalment_director');
    }

    public static function boot()
    {
        // parent -> instalment model (current)
        parent::boot();

        // $parent -> instalment model (current)
        // $related -> schedule model (child)

        static::hasManySaving(function($parent, $related){
            $related->instalment_id = $parent->id;
        });
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'instalments_index';
    }
    
    public function registerMediaCollections(): void
    {
        // $this->addMediaCollection('instalment-cover-collection')->singleFile();
        // $this->addMediaCollection('instalment-banner-collection')->singleFile();
        $this->addMediaCollection('instalment-gallery-collection');
    }
}
