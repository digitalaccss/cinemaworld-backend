<?php

namespace App\Nova;

use Illuminate\Http\Request;

use App\Models\Show;
use App\Models\Instalment;

use App\Nova\Traits\RedirectToParentDetail;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\BelongsTo;

use Laravel\Nova\Http\Requests\NovaRequest;

class Schedule extends Resource
{
    //use RedirectToParentDetail;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Schedule::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

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
            ID::make()->sortable(),
            Date::make('Start Date', 'schedule_start_date')
            ->sortable()
            ->rules('required'),
            Text::make('Start Time', 'schedule_start_time')
            ->sortable()
            ->placeholder('00:00:00')
            ->rules('required', 'date_format:"H:i:s"'),
            Date::make('End Date', 'schedule_end_date')
            ->rules('required'),
            Text::make('End Time', 'schedule_end_time')
            ->placeholder('00:00:00')
            ->rules('required', 'date_format:"H:i:s"'),

            Select::make('Show', 'show_id')
            ->options($this->getShowOptions())
            ->hideFromIndex()
            ->hideFromDetail()
            ->hideWhenUpdating()
            ->hide()
            ->dependsOn(
                $request->viaResource,
                function (Select $field, $request, FormData $formData) {
                    //$show = Show::select('show_type_id')->where('id', $formData->show)->first();
                    if($request->viaResource === null){
                        $field->show()->options($this->getShowOptions())
                        ->rules(['required']);
                    }
                }
            ),
            
            // BelongsTo::make('Show', 'show')
            // ->hideFromIndex()
            // ->hideFromDetail()
            // ->hideWhenUpdating()
            // ->hide()
            // ->dependsOn(
            //     $request->viaResource,
            //     function (BelongsTo $field, $request, FormData $formData) {
            //         //$show = Show::select('show_type_id')->where('id', $formData->show)->first();
            //         if($request->viaResource === null){
            //             $field->show();
            //             //->rules(['required']);
            //         }
            //     }
            // ),

            Select::make('Instalment', 'instalment_id')
            ->hideFromIndex()
            ->hideFromDetail()
            ->hideWhenUpdating()
            ->hide()
            ->dependsOn(
                'show_id',
                function (Select $field, NovaRequest $request, FormData $formData) {
                    //$show = Show::select('show_type_id')->where('id', $formData->show)->first();
                    if($formData->show_id){
                        $showId = (int) $formData->show_id;
                        $showTypeId = $this->getShowType($showId);
                        if ($showTypeId === 2) {
                            $field->show()
                            ->options($this->getInstalmentOptions((int)$formData->show_id))
                            ->rules(['required']);
                        }
                    }
                }
            ),

            Text::make('Show')->resolveUsing(function(){
                $show = Show::select('title', 'show_type_id')->where('id', $this->show_id)->first();
                if($show->show_type_id == 2){
                    //$instalmentTitle = Instalment::where('series_id', $showID)->value('title');
                    $instalmentId = $this->instalment_id;
                    $instalmentTitle = Instalment::where('id', $instalmentId)->value('title');
                    $show->title = $show->title.' - '.$instalmentTitle;
                }
                $showTitle = $show->title;
                return $showTitle;
            })->hideWhenCreating()->readonly(true)
            // Text::make('Show', 'show_id')->resolveUsing(function($showID){
            //     $show = Show::select('title', 'show_type_id')->where('id', $showID)->first();
            //     if($show->show_type_id == 2){
            //         //$instalmentTitle = Instalment::where('series_id', $showID)->value('title');
            //         $instalmentId = $this->instalment_id;
            //         $instalmentTitle = Instalment::where('id', $instalmentId)->value('title');
            //         $show->title = $show->title.' - '.$instalmentTitle;
            //     }
            //     $showTitle = $show->title;
            //     return $showTitle;
            // })->hideWhenCreating()->readonly(true)
        ];
    }

    private static function getInstalmentOptions(int $seriesId)
    {
        $instalmentsArr = [];
        $instalments = Instalment::select('id', 'title')->where('series_id', $seriesId)->get();

        if(count($instalments) >= 1){
            foreach($instalments as $instalment){
                $instalmentsArr[$instalment->id] = $instalment->title;
            }
        }

        return $instalmentsArr;
    }

    private static function getShowOptions()
    {
        $showsArr = [];
        $shows = Show::select('id', 'title')->orderBy('title')->get();

        if(count($shows) >= 1){
            foreach($shows as $show){
                $showsArr[$show->id] =  $show->title;
            }
        }

        return $showsArr;
    }

    private static function getShowType(int $showId)
    {
        $show = Show::select('show_type_id')->where('id', $showId)->first();

        return (int)$show->show_type_id;
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
                (new Actions\ImportSchedules())->standalone()
            ];
        }else{
            return[];
        }
        
    }
}
