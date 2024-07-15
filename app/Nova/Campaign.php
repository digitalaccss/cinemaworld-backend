<?php

namespace App\Nova;

use Illuminate\Http\Request;

use App\ModeLs\Carousel;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Slug;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Laravel\Nova\Fields\Image;

use Laravel\Nova\Fields\BelongsTo;

use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;

class Campaign extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Campaign::class;

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
            Text::make('Title')->sortable()->rules('required', 'max:255'),
            Text::make('Slogan', 'slogan')->sortable()->rules('max:255')->nullable(),
            // slug
            Slug::make('Slug', 'slug')->from('Title')->onlyOnForms()->rules('required', 'max:128'),
            // content
            NovaTinyMCE::make('Content', 'content')->rules('required')->hideFromDetail(),
            //Text::make('Facebook Link', 'facebook_link_url')->rules('active_url', 'max:255')->nullable(),
            // banner
            Image::make('Banner (1920px x 576px)', 'banner_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/campaign-banner-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->banner_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/campaign-banner-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            
            BelongsTo::make('Carousel', 'carousel')->rules('required'),
        ];
    }

    // public static function relatableCarousels(NovaRequest $request, $query)
    // {
    //     return $query->where('has_campaign', 0);
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
