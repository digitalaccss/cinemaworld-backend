<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;

// yes
use App\Nova\ShowTypes;
use Laravel\Nova\Fields\HasOne;

use Laravel\Nova\Http\Requests\NovaRequest;

class Films extends Resource
{
    public static $group = 'Shows';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Film::class;

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
            Text::make('Title')->sortable()->required(),
            Text::make('Foreign Title', 'foreign_title')->sortable()->required(),
            // release year - change to year picker only
            // Date::make('Release Year', 'release_year')->sortable()->required(),
            Number::make('Release Year', 'release_year')->sortable()->required(),

            // show type id
            // HasOne::make('Tags', 'tags'),
            HasOne::make('ShowTypes', 'showType'),

            Number::make('Runtime')->required(),
            Textarea::make('Short Description', 'short_desc')->required(),
            Textarea::make('Full Description', 'full_desc')->required(),
            Text::make('Trivia Description', 'trivia_desc')->required(),
            Text::make('Trailer Link', 'trailer_link_url')->required(),
            // tag id
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
