<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\File;
use Illuminate\Http\Request;

class ImportShows extends Action
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
        $import = new \App\Imports\ShowsImport();
        $import->onlySheets('Worksheet 1');
        \Maatwebsite\Excel\Facades\Excel::import($import, $fields->file);
        if(request()->session()->has('import_show_error')){
            $msg = request()->session()->get('import_show_error');
            request()->session()->forget('import_show_error');
            return Action::danger($msg);
        }
        return Action::message('Shows uploaded!');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            File::make('File')
            ->rules('required'),
        ];
    }
}
