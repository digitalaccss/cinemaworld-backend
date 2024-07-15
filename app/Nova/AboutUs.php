<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Laravel\Nova\Fields\TextArea;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Image;

use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\Validator;

class AboutUs extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AboutUs::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    //public static $banner_type = null;

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
        // print_r("hello");
        return [
            ID::make()->sortable(),

            Text::make('Title', 'title')->rules('required', 'max:150'),

            Select::make('Banner Type', 'banner_type')->options([
                'no banner' => 'No Banner',
                'image' => 'Image',
                'video' => 'Video',
            ])->displayUsingLabels(),
            // ->fillUsing(function(NovaRequest $request, $model, $attribute, $requestAttribute) {
            //     return null;
            // })
            // ->dependsOn(
            //     ['video_link_url', 'about_us_banner_path'],
            //     function (Select $field, $request, FormData $formData) {
            //         if(trim($formData->video_link_url) != "" || trim($formData->about_us_banner_path != "")){
            //             $field->readonly();
            //         }
            //     }
            // )
            // ->hideFromIndex()
            // ->hideFromDetail(),

            Image::make('Banner Image (1440px x 810px)', 'about_us_banner_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/about-us-banner-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->about_us_banner_path->getClientOriginalName();
                $newFileName = "about-us-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
                if(Storage::disk('public')->exists('img/about-us-banner-collection/'.$newFileName)){
                    $newFileName = "about-us-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
                }
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png')
            ->hide()
            ->hideFromIndex()
            ->hideFromDetail()
            ->dependsOn(
                'banner_type',
                function (Image $field, $request, FormData $formData) {
                    if($formData->banner_type == "image"){
                        $field->show();
                    }
                }
            ),

            Text::make('Video Link Url', 'video_link_url')->rules('max:255')
            ->hide()
            ->hideFromIndex()
            ->hideFromDetail()
            ->dependsOn(
                'banner_type',
                function (Text $field, $request, FormData $formData) {
                    if($formData->banner_type == "video"){
                        $field->show()->rules('required', 'active_url', 'max:255');
                    }
                }
            ),
            
            Text::make('Sub Title', 'sub_title')->rules('max:150'),

            NovaTinyMCE::make('Content', 'content')->rules('required')->hideFromDetail()
            ->options([
                'font_size_formats' => '8pt 9pt 10pt 11pt 12pt 14pt 18pt 20pt 24pt 30pt 36pt 45pt',
            ]),

            Text::make('Button Text', 'button_text')->rules('required', 'max:50'),

            Text::make('Button Link Url', 'button_link_url')->rules('required', 'max:255', 'active_url'),

            // NovaTinyMCE::make('Content (Desktop)', 'content')->rules('required')->hideFromDetail()
            // ->options([
            //     'font_size_formats' => '8pt 9pt 10pt 11pt 12pt 14pt 18pt 20pt 24pt 30pt 36pt 45pt',
            //     'extended_valid_elements' => 'iframe[src|title|frameborder|allow|width|height|class=AboutUsIframe]',
            //     'style_formats' => [
            //             [
            //                 'title' => 'Headings', 'items' => [
            //                     ['title' => 'Heading 1', 'block' => 'h1'], 
            //                     ['title' => 'Heading 2', 'block' => 'h2'],
            //                     ['title' => 'Heading 3', 'block' => 'h3'],
            //                     ['title' => 'Heading 4', 'block' => 'h4'],
            //                     ['title' => 'Heading 5', 'block' => 'h5'],
            //                     ['title' => 'Heading 6', 'block' => 'h6']
            //                 ]
            //             ],
            //             [
            //                 'title' => 'Blocks', 'items' => [
            //                     ['title' => 'p', 'block' => 'p'],
            //                     ['title' => 'div', 'block' => 'div'],
            //                     ['title' => 'pre', 'block' => 'pre'],
            //                 ]
            //             ],
            //             [
            //                 'title' => 'Iframe Div', 'items' => [
            //                     ['title' => 'Iframe Div', 'block' => 'div', 'classes' => 'IframeDiv']
            //                 ]
            //             ], 
            //         ],
            // ]),

            // NovaTinyMCE::make('Content (Mobile)', 'mobile_content')->rules('required')->hideFromDetail()
            // ->options([
            //     'font_size_formats' => '8pt 9pt 10pt 11pt 12pt 14pt 18pt 20pt 24pt 30pt 36pt 45pt',
            //     'extended_valid_elements' => 'iframe[src|title|frameborder|allow|width|height]',
            // ]),
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

    // for index page
    public static function label() {
        return 'About Us';
    }

    // for create and update page
    public static function singularLabel() {
        return 'About Us';
    }
}
