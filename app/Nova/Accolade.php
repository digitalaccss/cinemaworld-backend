<?php

namespace App\Nova;

use Illuminate\Http\Request;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;

use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Accolade extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Accolade::class;

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
            Text::make('Category', 'category')->sortable()->rules('required', 'max:100'),
            // cover photo
            Image::make('Cover Photo', 'cover_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/accolade-cover-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->cover_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->id . "-" . str_replace(' ', '-', $originalFileName);
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            Text::make('Cover Photo Alt', 'cover_photo_alt')->rules('max:255')->nullable()
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
                (new Actions\ImportAccolades())->showInline()->standalone(),

                (new DownloadExcel)->only('id', 'name', 'category')
                ->withHeadings('id', 'name', 'category')
            ];
        }else{
            return[];
        }

        // return [
        //     (new Actions\ImportAccolades())->showInline()->standalone(),

        //     (new DownloadExcel)->only('id', 'name', 'category')
        //     ->withHeadings('id', 'name', 'category')
        // ];
    }

    public static function usesScout()
    {
        return false;
    }
}
