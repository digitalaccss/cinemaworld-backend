<?php

namespace App\Nova;

use Illuminate\Http\Request;

use App\Models\Show;

//use App\Nova\Traits\RedirectToParentDetail;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\FormData;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\HasMany;

use Laravel\Nova\Http\Requests\NovaRequest;

use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Illuminate\Support\Facades\Storage;

class Instalment extends Resource
{
    //use RedirectToParentDetail;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Instalment::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    // public static $title = 'title';
    
    public function title()
    {
        $seriesTitle = $this->series->title;
        $instalmentTitle = $this->title;

        $fullTitle = $seriesTitle.' - '.$instalmentTitle;
        
        return $fullTitle;
    }

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
            Text::make('House Media ID', 'house_media_id')->sortable()->rules('required', 'max:255'),
            Text::make('Series', 'series_id')->resolveUsing(function($seriesID){
                $seriesTitle = Show::where('id', $seriesID)->value('title');
                return $seriesTitle;
            })->hideWhenCreating()->hideWhenUpdating()->readonly(true),

            Select::make('Series', 'series_id')
            ->options($this->getSeriesOptions())
            ->hideFromIndex()
            ->hideFromDetail()
            //->hideWhenUpdating()
            ->hide()
            ->dependsOn(
                $request->viaResource,
                function (Select $field, $request, FormData $formData) {
                    //$show = Show::select('show_type_id')->where('id', $formData->show)->first();
                    if($request->viaResource === null){
                        $field->show()->options($this->getSeriesOptions())
                        ->rules(['required']);
                    }
                }
            ),

            Text::make('Title', 'title')->sortable()->rules('required', 'max:255'),
            Text::make('Foreign Title', 'foreign_title')->rules('max:255')->nullable()->sortable(),
            Text::make('Sub Title', 'sub_title')->rules('max:50')->nullable()->sortable(),
            // slug
            Slug::make('Slug', 'slug')->from('Title')->onlyOnForms()->rules('required', 'max:100'),
            // instalment number
            Number::make('Instalment Number', 'instalment_number')->sortable()->rules('required'),
            //Number::make('Instalment Number', 'instalment_number')->sortable()->rules('required', 'digits:3'),
            // release year
            Number::make('Release Year', 'release_year')->sortable()->rules('required', 'digits:4'),
            Number::make('Runtime')->rules('required', 'gt:0'),
            Boolean::make('Publish', 'is_publish')->filterable(),
            Boolean::make('New', 'is_new'),
            Boolean::make('On Demand', 'on_demand'),
            //Textarea::make('Short Description', 'short_desc')->rules('required', 'max:500'),
            Textarea::make('Short Description', 'short_desc')->rules('max:512'),
            Textarea::make('Full Description', 'full_desc')->rules('max:2000'),
            Textarea::make('Trivia Description', 'trivia_desc')->rules('max:1024')->nullable(),
            Text::make('Trailer Link', 'trailer_link_url')->rules('max:255')->nullable(),
            Date::make('Expiry Date', 'expiry_date')->filterable(),
        
            // cover photo
            Image::make('Cover Photo (540px x 300px)', 'cover_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/instalment-cover-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->cover_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/instalment-cover-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            Text::make('Cover Photo Alt', 'cover_photo_alt')->rules('max:255')->nullable(),
            // banner
            Image::make('Banner (1920px x 1080px)', 'banner_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/instalment-banner-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->banner_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/instalment-banner-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            Text::make('Banner Alt', 'banner_alt')->rules('max:255')->nullable(),
            // photo gallery
            Images::make('Photo Gallery (540px x 300px)', 'instalment-gallery-collection')
            ->croppable(false)
            ->enableExistingMedia(),
            //->rules('required'),
            
            // leaderboard banner photo
            // Image::make('Leaderboard Banner', 'leaderboard_banner_path')
            // ->squared()
            // ->maxWidth(150)
            // ->disk('public')
            // ->path('img/instalment-leaderboard-banner-collection')
            // ->storeAs(function (Request $request){
            //     $originalFileName = $request->leaderboard_banner_path->getClientOriginalName();
            //     $newFileName = str_replace(' ', '-', $originalFileName);
            //     return $newFileName;
            // })
            // ->prunable()
            // ->acceptedTypes('image/jpeg,image/png'),

            // tonight banner photo (web)
            Image::make('Tonight Banner (Web-3350px × 350px)', 'web_tonight_banner_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/instalment-tonight-banner-web-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->web_tonight_banner_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/instalment-tonight-banner-web-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),

            // tonight banner photo (mobile)
            Image::make('Tonight Banner (Mobile-780px × 564px)', 'mobile_tonight_banner_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/instalment-tonight-banner-mobile-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->mobile_tonight_banner_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/instalment-tonight-banner-mobile-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            Text::make('Tonight Banner Alt', 'tonight_banner_alt')->rules('max:255')->nullable(),
            // languages
            BelongsToMany::make('Languages', 'languages'),
            // schedules
            HasMany::make('Schedules', 'schedules'),
            // directors
            BelongsToMany::make('Directors', 'directors'),
            // cast
            BelongsToMany::make('Cast', 'cast'),
            Text::make('Meta Title', 'meta_title')->sortable()->rules('max:70'),
            Textarea::make('Meta Description', 'meta_description')->rules('max:165')->nullable()->sortable(),   
        ];
    }

    private static function getSeriesOptions()
    {
        $seriesArr = [];
        $series = Show::select('id', 'title', 'show_type_id')->where('show_type_id', 2)->orderBy('title')->get();

        if(count($series) >= 1){
            foreach($series as $serie){
                $seriesArr[$serie->id] =  $serie->title;
            }
        }

        return $seriesArr;
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
        return [
            (new Actions\PublishInstalments())->showInline()
            ->confirmText('Are you sure you want to publish this instalment?')
            ->confirmButtonText('Publish')
            ->cancelButtonText("Cancel"),
            
            (new Actions\UnpublishInstalments())->showInline()
            ->confirmText('Are you sure you want to unpublish this instalment?')
            ->confirmButtonText('Unpublish')
            ->cancelButtonText("Cancel"),

            (new Actions\ExportInstalments())->showInline()
        ];
    }

    public static function usesScout()
    {
        return false;
    }
}
