<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Instalment;

class UnpublishInstalments extends Action
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
            return Action::danger('No instalment selected');
        }

        //$shows = Show::all();
        $instalments = Instalment::all();
        $instalmentUpdated = null;
        //$showUpdated = null;
        foreach($models as $model){
            $instalment = $instalments->where('id', $model->id)->first();
            //$show = $shows->where('id', $model->id)->first();
            if($instalment){
                $instalmentUpdated = $instalment->update(['is_publish' => false]);
            }
        }

        if($instalmentUpdated){
            return Action::message('Instalment unpublished successfully!');
        }
        else{
            return Action::danger('Instalment unpublished unsuccessfully!');
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
