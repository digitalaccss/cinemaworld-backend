<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Show;
use App\Models\Instalment;

class UnpublishShows extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() < 1) {
            return Action::danger('No show selected');
        }

        $shows = Show::all();
        $instalments = Instalment::all();
        $showUpdated = null;
        foreach($models as $model){
            $show = $shows->where('id', $model->id)->first();
            if($show){
                $showUpdated = $show->update(['is_publish' => false]);
                // if($show->show_type_id == 2){
                //     $seriesInstalments = $instalments->where('series_id', $show->id);
                //     if($seriesInstalments->count() > 0){
                //         foreach($seriesInstalments as $instalment){
                //             $instalment->update(['is_publish' => false]);
                //         }
                //     } 
                // }
            }
        }

        if($showUpdated){
            return Action::message('Show unpublished successfully!');
        }
        else{
            return Action::danger('Show unpublished unsuccessfully!');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
