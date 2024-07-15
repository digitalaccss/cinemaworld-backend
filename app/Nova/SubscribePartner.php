<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\Country;

// use App\Models\Country;

class SubscribePartner extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\SubscribePartner::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'partner';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'partner',
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
            Text::make('Partner', 'partner')->sortable()->rules('required', 'max:120'),
            //Text::make('Country', 'country')->sortable()->rules('required', 'max:70'),
            //Select::make('Country', 'country_id')->options($this->getCountryOptions())->rules('required')->hideFromDetail(),
            BelongsTo::make('Country', 'country')->rules('required'),
            Text::make('Link', 'link_url')->sortable()->rules('required', 'max:255', 'active_url'),
            Boolean::make('Pay TV', 'pay_tv'),
            Boolean::make('Streaming', 'streaming'),
        ];
    }

    // private static function getCountryOptions()
    // {
    //     $countriesArr = [];
    //     $countries = Country::select('id', 'country_display_name', 'country_name')->orderBy('country_display_name')->get();

    //     if(count($countries) >= 1){
    //         foreach($countries as $country){
    //             $countriesArr[$country->id] =  $country->country_display_name;
    //         }
    //     }

    //     return $countriesArr;
    // }

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
