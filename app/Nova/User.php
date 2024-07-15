<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Image;


use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
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
            Text::make('Name', 'name')
            ->sortable()
            ->rules('required', 'max:255'),
            Text::make('Email', 'email')
            ->sortable()
            ->rules('required', 'email', 'max:254')
            ->creationRules('unique:users,email')
            ->updateRules('unique:users,email,{{resourceId}}'),
            Password::make('Password', 'password')
            ->onlyOnForms()
            ->creationRules('required', Rules\Password::defaults())
            ->updateRules('nullable', Rules\Password::defaults()),
            // profile photo
            Image::make('Profile Photo', 'profile_photo_path')
            ->squared()
            ->maxWidth(150)
            ->disk('public')
            ->path('img/user-collection')
            ->storeAs(function (Request $request){
                $originalFileName = $request->profile_photo_path->getClientOriginalName();
                $newFileName = str_replace(' ', '-', $originalFileName);
                return $newFileName;
            })
            ->prunable()
            ->acceptedTypes('image/jpeg,image/png'),
            Text::make('Created At', 'created_at', function($dateTimeStr){
                $timestamp = strtotime($dateTimeStr);
                $dateTimeStr = date('m/d/Y, h:i A', $timestamp).' GMT+8';
                return $dateTimeStr;
            })->sortable()->onlyOnIndex()
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
        return [
            (new Actions\ImportUsers())->showInline()->standalone()
        ];
    }

}
