<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\BelongsTo;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Laravel\Nova\Fields\Image;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\BlogType;
use App\Nova\Blog;
use Laravel\Nova\Fields\Select;
use Illuminate\Support\Facades\Storage;

class Blog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Blog::class;

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
            Text::make('Title', 'title')->sortable()->rules('required', 'max:255'),
            Text::make('Slogan', 'slogan')->sortable()->rules('max:255')->nullable(),
            // slug
            Slug::make('Slug', 'slug')->from('Title')->onlyOnForms()->rules('required', 'max:128'),
            //Hidden::make('Blog Type ID', 'blog_type_id')->default(1),

            Text::make('Description', 'description')->rules('max:255')->nullable(),

            // content
            NovaTinyMCE::make('Content', 'content')->rules('required')->hideFromDetail(),
            //Text::make('Facebook Link', 'facebook_link_url')->rules('active_url', 'max:255')->nullable(),

            // cover photo
            Image::make('Cover Photo (1280px x 720px)', 'cover_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/blog-cover-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->cover_photo_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/blog-cover-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            Text::make('Cover Photo Alt', 'cover_photo_alt')->sortable()->nullable(),
            // banner
            Image::make('Banner (1280px x 720px)', 'banner_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/blog-banner-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->banner_path->getClientOriginalName();
                //$newFileName = str_replace(' ', '-', $originalFileName);
                $newFileName = $this->slug . "-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/blog-banner-collection/'.$newFileName)){
                    $newFileName = $this->slug . "-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            Text::make('Banner Alt', 'banner_alt')->sortable()->nullable(),
            BelongsTo::make('Blog Type', 'blogType')->rules('required'),
            // is archived?
            //Boolean::make('Archive This?', 'is_archived'),
            //DateTime::make('Published At', 'created_at'),
            Text::make('Meta Title', 'meta_title')->sortable()->rules('max:70'),
            Textarea::make('Meta Description', 'meta_description')->rules('max:165')->nullable()->sortable(),   
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        //return $query->where('blog_type_id', 1);
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

    public static function usesScout()
    {
        return false;
    }
}
