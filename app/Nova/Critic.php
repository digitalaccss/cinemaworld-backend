<?php

namespace App\Nova;

use Illuminate\Http\Request;

use App\Models\Show;

use App\Nova\Traits\RedirectToParentDetail;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

use Laravel\Nova\Http\Requests\NovaRequest;

class Critic extends Resource
{
    use RedirectToParentDetail;
    
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Critic::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
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
            Text::make('Name', 'name')->sortable()->rules('required', 'max:100'),
            Textarea::make('Critique', 'critique')->sortable()->rules('required', 'max:100'),
            Text::make('Show', 'show_id')->resolveUsing(function($showID){
                $showTitle = Show::where('id', $showID)->value('title');
                return $showTitle;
            })->hideWhenCreating()->readonly(true)
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
