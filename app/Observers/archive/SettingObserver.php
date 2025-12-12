<?php

namespace App\Observers;

use App\Models\Setting;
use App\Models\Carousel;

class SettingObserver
{
    /**
     * Handle the Setting "updated" event.
     *
     * @param  \App\Models\Setting  $setting
     * @return void
     */
    // executes when new row is successfully inserted into settings table
    public function updated(Setting $setting)
    {
        $activeCarouselIDs = $setting->active_carousels;

        foreach($activeCarouselIDs as $activeCarouselID){
            $ifCarouselExist = Carousel::where('tag_id', $activeCarouselID)->exists();

            if(!($ifCarouselExist)){
                $newActiveCarousel = new Carousel();
                $newActiveCarousel->tag_id = $activeCarouselID;
                $newActiveCarousel->save();
            }
        }
    }
}
