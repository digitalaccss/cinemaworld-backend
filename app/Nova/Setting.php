<?php

namespace App\Nova;

use Illuminate\Http\Request;

use App\Nova\Traits\RedirectToIndex;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Setting extends Resource
{
    use RedirectToIndex;
    
    // public static $group = 'Settings';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Setting::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'site_title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    // public static $search = [
    //     'id',
    // ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(),
            Text::make('Site Title', 'site_title')->rules('required', 'max:50'),
            Text::make('Site Link', 'site_address_url')->rules('required', 'active_url', 'max:255'),
            Text::make('Meta Title', 'meta_title')->rules('required', 'max:70'),
            Textarea::make('Meta Description', 'meta_description')->rules('required', 'max:165'),
            Text::make('Page Header (display at the bottom of the page)', 'page_header')->rules('max:80')->hideFromIndex(),
            Textarea::make('Page Description (display at the bottom of the page)', 'page_description')->rules('max:220')
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
