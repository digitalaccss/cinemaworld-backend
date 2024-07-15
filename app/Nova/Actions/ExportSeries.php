<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportSeries extends DownloadExcel implements WithMapping, withHeadings
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */

    public function map($show): array
    {
        return [
            $show->id,
            $show->title,
            $show->foreign_title,
            $show->slug,
            $show->short_desc,
            $show->full_desc,
            $show->trivia_desc,
            $show->director_statement, 
            //Date::dateTimeToExcel($show->expiry_date),
            //$show->expiry_date == null? null : $show->expiry_date->format('Y-m-d') ,
        ];

    }

    public function headings(): array
    {
        return [
            'id',
            //'house media id',
            'title english',
            'title foreign',
            'slug',
            'short description',
            'full description',
            'trivia description',
            'director statement',
        ];
    }

    // public function handle(ActionFields $fields, Collection $models)
    // {
    //     //
    // }

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
