<?php

namespace App\Nova\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;

trait RedirectToParentDetail
{
    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return \Laravel\Nova\URL|string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return static::redirectToParentDetail($request);
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return \Laravel\Nova\URL|string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return static::redirectToParentDetail($request);
    }

    /**
     * Return the location to redirect the user after deletion.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\URL|string|null
     */
    public static function redirectAfterDelete(NovaRequest $request)
    {
        return static::redirectToParentDetail($request);
    }

    /* Redirect the user to the resource' detail page. */
    public static function redirectToParentDetail($request)
    {
        // e.g. /resources/resourceName (index page)
        return '/resources/'.$request->viaResource.'/'.$request->viaResourceId;
    }
}