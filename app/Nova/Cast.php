<?php

namespace App\Nova;

use Illuminate\Http\Request;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Textarea;

use Laravel\Nova\Fields\Image;

use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Cast extends Resource
{
    public static $group = 'Shows';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Cast::class;

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
            Text::make('Name', 'name')->sortable()->rules('required', 'max:50'),
            // slug
            Slug::make('Slug', 'slug')->from('Name')->onlyOnForms()->rules('required', 'max:50'),
            Textarea::make('Description', 'description')->rules('max:1024'),

            Image::make('Profile Photo (250px x 250px)', 'profile_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/cast-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->profile_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/cast-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            Text::make('Profile Photo Alt', 'profile_photo_alt')->rules('max:255')->nullable(),
            Text::make('Created At', 'created_at', function($dateTimeStr){
                $timestamp = strtotime($dateTimeStr);
                return date("Y-m-d H:i:s", $timestamp);
            })->sortable()->onlyOnIndex(),
            Text::make('Meta Title', 'meta_title')->sortable()->rules('max:70'),
            Textarea::make('Meta Description', 'meta_description')->rules('max:165')->nullable()->sortable(),   
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
        if(!$request->has('viaResource') && !$request->has('viaResourceId')){
            return [
                (new DownloadExcel)->only('id', 'name', 'slug', 'description')
                ->withHeadings('id', 'name', 'slug', 'description')
            ];
        }else{
            return[];
        }

        // return [
        //     (new DownloadExcel)->only('id', 'name', 'slug', 'description')
        //     ->withHeadings('id', 'name', 'slug', 'description')
        // ];
    }

    public static function usesScout()
    {
        return false;
    }
}
