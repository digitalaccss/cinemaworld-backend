<?php

namespace App\Nova;

use Illuminate\Http\Request;

use App\Nova\ShowType;
use App\Nova\Region;
use App\Nova\Director;
use App\Nova\Cast;
use App\Nova\Tag;

use App\Models\Genre;
use App\Models\Country;
use App\Models\Language;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Outl1ne\MultiselectField\Multiselect;
use Laravel\Nova\Fields\Boolean;

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Laravel\Nova\Fields\Image;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;

use Laravel\Nova\Http\Requests\NovaRequest;

use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Illuminate\Support\Facades\Storage;

class Film extends Resource
{
    public static $group = 'Shows';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Show::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('House Media ID', 'house_media_id')->sortable()->rules('required', 'max:255'),
            Text::make('Title', 'title')->sortable()->rules('required', 'max:255'),
            Text::make('Foreign Title', 'foreign_title')->rules('max:255')->nullable()->sortable(),
            Text::make('Sub Title', 'sub_title')->rules('max:50')->nullable()->sortable(),
            // slug
            Slug::make('Slug', 'slug')->from('Title')->onlyOnForms()->rules('required', 'max:100'),
            // show type
            // BelongsTo::make('Show Type', 'showType'),
            Hidden::make('Show Type ID', 'show_type_id')->default(1),
            // genres test 1
            // Multiselect::make('Genres', 'genres')
            // ->options($this->getGenreOptions())->placeholder('—')->rules('required'),
            // region
            BelongsTo::make('Region', 'region')->rules('required'),
            // countries test 1
            // Multiselect::make('Countries', 'countries')
            // ->options($this->getCountryOptions())->placeholder('—')->rules('required'),
            // languages test 1
            // Multiselect::make('Languages', 'languages')
            // ->options($this->getLanguageOptions())->placeholder('—')->rules('required'),

            // release year - change to year picker only
            // Date::make('Release Year', 'release_year')->sortable()->required(),

            Number::make('Release Year', 'release_year')->sortable()->rules('required', 'digits:4')->nullable(),

            Number::make('Runtime')->rules('required', 'gt:0')->nullable(),
            Boolean::make('Publish', 'is_publish')->filterable(),
            Boolean::make('New', 'is_new'),
            Boolean::make('On Demand', 'on_demand'),
            Textarea::make('Short Description', 'short_desc')->rules('max:512'),
            Textarea::make('Full Description', 'full_desc')->rules('max:2000'),
            Textarea::make('Trivia Description', 'trivia_desc')->rules('max:1024')->nullable(),
            Text::make('Trailer Link', 'trailer_link_url')->rules('max:255')->nullable(),
            Date::make('Expiry Date', 'expiry_date')->filterable(),

            // director's statement
            NovaTinyMCE::make("Director's Statement", 'director_statement')->nullable()->hideFromDetail(),

            // cover photo
            Image::make('Cover Photo (252px x 379px)', 'cover_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/film-cover-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->cover_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName); 
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/film-cover-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            // banner
            Image::make('Banner (1920px x 1080px)', 'banner_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/film-banner-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->banner_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/film-banner-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            // photo gallery
            Images::make('Photo Gallery (540px x 300px)', 'film-gallery-collection')
            ->croppable(false)
            ->enableExistingMedia(),

            // director statement photo
            Image::make("Director's Statement Photo (540px x 300px)", 'director_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/director-statement-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->director_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/director-statement-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            // leaderboard banner photo
            // Image::make('Leaderboard Banner', 'leaderboard_banner_path')
            // ->squared()
            // ->maxWidth(150)
            // ->disk('public')
            // ->path('img/film-leaderboard-banner-collection')
            // ->storeAs(function (Request $request){
            //     $originalFileName = $request->leaderboard_banner_path->getClientOriginalName();
            //     $newFileName = str_replace(' ', '-', $originalFileName);
            //     return $newFileName;
            // })
            // ->prunable()
            // ->acceptedTypes('image/jpeg,image/png'),

            // tonight banner photo (web)
            Image::make('Tonight Banner (Web-3350px × 350px)', 'web_tonight_banner_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/film-tonight-banner-web-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->web_tonight_banner_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/film-tonight-banner-web-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),

            // tonight banner photo (mobile)
            Image::make('Tonight Banner (Mobile-780px × 564px)', 'mobile_tonight_banner_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/film-tonight-banner-mobile-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->mobile_tonight_banner_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/film-tonight-banner-mobile-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),

            // genres
            BelongsToMany::make('Genres', 'genres'),
            // countries
            BelongsToMany::make('Countries', 'countries'),
            // languages
            BelongsToMany::make('Languages', 'languages'),
            // schedules
            HasMany::make('Schedules', 'schedules'),
            // accolades
            BelongsToMany::make('Accolades', 'accolades'),
            // critics
            HasMany::make('Critics', 'critics'),
            // directors
            BelongsToMany::make('Directors', 'directors'),
            // cast
            BelongsToMany::make('Cast', 'cast'),
            // tags
            BelongsToMany::make('Tags', 'tags')->nullable()
        ];
    }

    // private static function getGenreOptions()
    // {
    //     $genreArr = [];
    //     $genres = Genre::select('id', 'genre_display_name')->get();

    //     if(count($genres) >= 1){
    //         foreach($genres as $genre){
    //             $genreArr[$genre->id] =  $genre->genre_display_name;
    //         }
    //     }

    //     return $genreArr;
    // }

    // private static function getCountryOptions()
    // {
    //     $countryArr = [];
    //     $countries = Country::select('id', 'country_display_name')->get();

    //     if(count($countries) >= 1){
    //         foreach($countries as $country){
    //             $countryArr[$country->id] = $country->country_display_name;
    //         }
    //     }

    //     return $countryArr;
    // }

    // private static function getLanguageOptions()
    // {
    //     $languageArr = [];
    //     $languages = Language::select('id', 'language_display_name')->get();

    //     if(count($languages) >= 1){
    //         foreach($languages as $language){
    //             $languageArr[$language->id] = $language->language_display_name;
    //         }
    //     }

    //     return $languageArr;
    // }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('show_type_id', 1);
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            (new Actions\ImportShows())->showInline()->standalone(),

            (new Actions\PublishShows())->showInline()
            ->confirmText('Are you sure you want to publish this film?')
            ->confirmButtonText('Publish')
            ->cancelButtonText("Cancel"),
            
            (new Actions\UnpublishShows())->showInline()
            ->confirmText('Are you sure you want to unpublish this film?')
            ->confirmButtonText('Unpublish')
            ->cancelButtonText("Cancel"),
            
            (new Actions\ExportShows())->showInline()
        ];
    }

    public static function authorizable()
    {
        return false;
    }

    public static function usesScout()
    {
        return false;
    }
}
