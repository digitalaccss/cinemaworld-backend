<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Log;

class ScheduleFile extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ScheduleFile::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'file_path';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'year',
        'file_path'
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

            Select::make('Year', 'year')->options(function () {
                $array= [];
                $currentYear = date('Y');
                for($i = $currentYear + 1; $i >= $currentYear - 3; $i -- ){
                    $array[$i] = ['label' => $i];
                }
                return $array;
            })
            ->displayUsingLabels()
            ->rules('required')
            ->sortable(),

            Select::make('Month', 'month')->options([
                '1' => 'January',
                '2' => 'February',
                '3' => 'March',
                '4' => 'April',
                '5' => 'May',
                '6' => 'June',
                '7' => 'July',
                '8' => 'August',
                '9' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            ])->displayUsingLabels()
            ->rules('required')
            ->sortable(),
            
            File::make('Schedule File', 'file_path')
            ->disk('public')
            ->path('schedule-file')
            ->storeAs(function (Request $request){
                $originalFileName = $request->file_path->getClientOriginalName();
                $newFileName = str_replace(' ', '-', $originalFileName);
                //$newFileName = $request->year.'-'.$request->month.'.'.pathinfo($request->file_path->getClientOriginalName(), PATHINFO_EXTENSION);
                return $newFileName;
            })
            // ->resolveUsing(function ($name){
            //     $displayFileName = str_replace('schedule-file/', '', $name);
            //     return $displayFileName;
            // })
            ->prunable()
            ->acceptedTypes('.pdf')
            ->creationRules('file', 'mimes:pdf', 'required')
            ->deletable(false)
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
