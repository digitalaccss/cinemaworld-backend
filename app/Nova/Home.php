<?php

namespace App\Nova;

use Illuminate\Http\Request;

use App\Models\Show;

use App\Models\Show as ShowModel;
use App\Models\Carousel;
use App\Models\Banner;

use App\Nova\Traits\RedirectToIndex;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
// use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Panel;
use Outl1ne\MultiselectField\Multiselect;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaColorField\Color;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\FormData;

class Home extends Resource
{
  use RedirectToIndex;

  /**
   * The model the resource corresponds to.
   *
   * @var string
   */
  public static $model = \App\Models\Home::class;

  /**
   * The single value that should be used to represent the resource when being displayed.
   *
   * @var string
   */
  // public static $title = 'Home';

  public function title()
  {
    $title = $this->featuredShow->title;

    return $title;
  }

  /**
   * The columns that should be searched.
   *
   * @var array
   */
  public static $search = [
    'id',
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
      // featured show type
      // BelongsTo::make('Featured Show Type', 'showType', 'App\Nova\ShowType')->rules('required'),
      Text::make('Featured Show', 'featured_show_id')->resolveUsing(function ($featuredShowID) {
        $showTitle = Show::where('id', $featuredShowID)->value('title');
        return $showTitle;
      })->exceptOnForms(),
      // featured show
      Select::make('Featured Show', 'featured_show_id')
        ->options($this->getShowOptions())->placeholder('—')->searchable()->rules('required')->onlyOnForms(),

      // featured banner
      Image::make('Featured Banner (Web-1920px x 1080px)', 'featured_banner_path')
        ->squared()
        ->maxWidth(150)
        ->disk('public')
        ->path('img/featured-banner-collection')
        ->storeAs(function (Request $request) {
          $originalFileName = $request->featured_banner_path->getClientOriginalName();
          //$newFileName = str_replace(' ', '-', $originalFileName);
          //$newFileName = strtolower($newFileName);
          $newFileName = "home-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
          if (Storage::disk('public')->exists('img/featured-banner-collection/' . $newFileName)) {
            $newFileName = "home-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
          }
          return $newFileName;
        })
        ->prunable()
        ->acceptedTypes('image/jpeg,image/png'),

      // featured banner mobile
      Image::make('Featured Banner (Mobile-810px x 1080px)', 'mobile_featured_banner_path')
        ->squared()
        ->maxWidth(150)
        ->disk('public')
        ->path('img/featured-banner-collection')
        ->storeAs(function (Request $request) {
          $originalFileName = $request->mobile_featured_banner_path->getClientOriginalName();
          // $newFileName = str_replace(' ', '-', $originalFileName);
          // $newFileName = "mobile-" . strtolower($newFileName);
          $newFileName = "home-" . $this->id . "-mobile-" . str_replace(' ', '-', strtolower($originalFileName));
          if (Storage::disk('public')->exists('img/featured-banner-collection/' . $newFileName)) {
            $newFileName = "home-" . $this->id . "-mobile-2-" . str_replace(' ', '-', strtolower($originalFileName));
          }
          return $newFileName;
        })
        ->prunable()
        ->acceptedTypes('image/jpeg,image/png'),

      // active carousels
      Multiselect::make('Active Carousels', 'active_carousels')
        ->options($this->getCarouselOptions())->placeholder('—')->reorderable()->nullable(),

      // customize banner
      Boolean::make('Publish Customize Banner', 'is_publish_customize_banner'),

      Text::make('Customize Banner Text', 'customize_banner_display_text')
        ->rules('required', 'max:80'),

      Color::make('Customize Banner Text Colour', 'customize_banner_text_colour')->sketch()->rules('required'),

      Color::make('Customize Banner Background Colour', 'customize_banner_background_colour')->sketch()->rules('required'),

      Image::make('Customize Banner Image (512px x 288px)', 'customize_banner_image_path')
        ->squared()
        ->maxWidth(150)
        ->disk('public')
        ->path('img/customize-banner-image-collection')
        ->storeAs(function (Request $request) {
          $originalFileName = $request->customize_banner_image_path->getClientOriginalName();
          //$newFileName = str_replace(' ', '-', $originalFileName);
    
          $newFileName = "home-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
          if (Storage::disk('public')->exists('img/customize-banner-image-collection/' . $newFileName)) {
            $newFileName = "home-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
          }
          return $newFileName;
        })
        ->prunable()
        ->deletable(false)
        ->acceptedTypes('image/jpeg,image/png')
        ->creationRules('required'),

      Text::make('Customize Banner Button Text', 'customize_banner_button_text')->rules('required', 'max:30'),
      //NovaTinyMCE::make('Customize Banner Button Text', 'customize_banner_button_text')->rules('required')->hideFromDetail(),

      Color::make('Customize Banner Button Text Colour', 'customize_banner_button_text_colour')->sketch()->rules('required'),

      Color::make('Customize Banner Button Colour', 'customize_banner_button_colour')->sketch()->rules('required'),

      Text::make('Customize Banner Link', 'customize_banner_button_link_url')->rules('required', 'active_url'),

      new Panel('Popup Information', $this->popupFields($request))
    ];
  }

  protected function popupFields(NovaRequest $request)
  {
    return [
      Boolean::make('Show Popup', 'is_show_popup'),
      Select::make('Popup Type', 'popup_type')->options([
        'image/gif' => 'Image / GIF',
        'video' => 'Video'
      ])
        ->displayUsingLabels()
        ->dependsOn('is_show_popup', function (Select $field, $request, FormData $formData) {
          if ($formData->is_show_popup == true) {
            $field->show()->rules('required');
          }
        }),

      Image::make('Popup Image', 'popup_image_path')
        ->hide()
        ->hideFromIndex()
        ->hideFromDetail(function(NovaRequest $request) {
          return $this->popup_type !== 'image/gif';
        })
        // ->deletable(false)
        ->squared()
        ->maxWidth(150)
        ->disk('public')
        ->path('img/popup-image-collection')
        ->storeAs(function (Request $request) {
          $originalFileName = $request->popup_image_path->getClientOriginalName();
          //$newFileName = str_replace(' ', '-', $originalFileName);
          //$newFileName = strtolower($newFileName);
          $newFileName = "home-" . $this->id . "-" . str_replace(' ', '-', strtolower($originalFileName));
          if (Storage::disk('public')->exists('img/popup-image-collection/' . $newFileName)) {
            $newFileName = "home-" . $this->id . "-2-" . str_replace(' ', '-', strtolower($originalFileName));
          }
          return $newFileName;
        })
        ->prunable()
        // ->acceptedTypes('image/jpeg,image/png');
        ->acceptedTypes('image/jpeg,image/png,image/gif')
        ->dependsOn('popup_type', function (Image $field, $request, FormData $formData) {
          if ($formData->popup_type == "image/gif") {
            $field->show();
            if(!$field->value){
              $field->rules('required');
            }
          }
        }),

      Text::make('Video Link URL', 'popup_video_link')
      ->hide()
      ->hideFromIndex()
        ->hideFromDetail(function () {
          return $this->popup_type !== 'video';
        })
      ->dependsOn('popup_type', function (Text $field, $request, FormData $formData) {
        if ($formData->popup_type == "video") {
          $field->show()
          ->rules('required', 'active_url');
        }
      }),

      Text::make('External Link URL', 'popup_external_link')->rules('nullable', 'active_url'),

      Boolean::make('Open in New Tab', 'is_popup_open_in_new_tab'),

      Text::make('Video Button Text', 'popup_video_button_text')
        ->hide()
        ->hideFromIndex()
        ->hideFromDetail(function () {
          return $this->popup_type !== 'video';
        })
        ->dependsOn('popup_type', function (Text $field, $request, FormData $formData) {
          if ($formData->popup_type == "video") {
            $field->show()->showOnDetail();
          }
        }),

      Color::make('Video Button Text Colour', 'popup_video_button_text_colour')
        ->hide()
        ->hideFromIndex()
        ->hideFromDetail(function () {
          return $this->popup_type !== 'video';
        })
        ->dependsOn('popup_type', function (Color $field, $request, FormData $formData) {
          if ($formData->popup_type == "video") {
            $field->show()->sketch();
          }
        }),

      Color::make('Video Button Colour', 'popup_video_button_colour')
        ->hide()
        ->hideFromIndex()
        ->hideFromDetail(function () {
          return $this->popup_type !== 'video';
        })
        ->dependsOn('popup_type', function (Color $field, $request, FormData $formData) {
          if ($formData->popup_type == "video") {
            $field->show()->sketch();
          }
        }), 
    ];
  }

  private static function getShowOptions()
  {
    $showsArr = [];
    $shows = ShowModel::select('id', 'title')->get();

    if (count($shows) >= 1) {
      foreach ($shows as $show) {
        $showsArr[$show->id] = $show->title;
      }
    }

    return $showsArr;
  }

  private static function getCarouselOptions()
  {
    $carouselArr = [];
    $carousels = Carousel::select('id', 'carousel_display_name')->get();

    if (count($carousels) >= 1) {
      foreach ($carousels as $carousel) {
        $carouselArr[$carousel->id] = $carousel->carousel_display_name;
      }
    }

    return $carouselArr;
  }

  /**
   * Build an "index" query for the given resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  // public static function indexQuery(NovaRequest $request, $query)
  // {
  //     return $query->where('id', 1);
  // }

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