<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\ShowType;
use App\Nova\Region;
use App\Nova\Director;
use App\Nova\Cast;
use App\Nova\Tag;

use App\Models\Genre;
use App\Models\Country;
use App\Models\Language;

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

class Show extends Resource
{
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
        
    ];

    public static $displayInNavigation = false;

    public static $globallySearchable = false;

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

            Text::make('House Media ID', 'house_media_id')->sortable(),

            Text::make('Title')->sortable(),

            Text::make('Foreign Title', 'foreign_title')->sortable(),

            Text::make('Show Type', function () {
                switch($this->show_type_id){
                    case 1: return sprintf('Films');
                    break;
                    case 2: return sprintf('Series');
                    break;
                    case 3: return sprintf('Documentaries');
                    break;
                    case 4: return sprintf('Shorts');
                    break;
                    default: return sprintf('Unknown show type');
                    break;
                }
                
            })->sortable(),

            //Hidden::make('Show Type ID', 'show_type_id')->default(1),

            BelongsTo::make('Region', 'region')->sortable(),

            Number::make('Release Year', 'release_year')->sortable(),

            Boolean::make('Publish', 'is_publish'),

            Boolean::make('New', 'is_new'),

            Date::make('Expiry Date', 'expiry_date')->sortable(),
        ];
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
        return [];
    }
}
