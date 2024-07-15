<?php

namespace App\Observers;

use App\Models\Campaign;
use App\Models\Carousel;

class CampaignObserver
{
    /**
     * Handle the Campaign "created" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function created(Campaign $campaign)
    {
        $campaignCarouselID = $campaign->carousel_id;

        // if campaign have carousel, cannot be 0 or null
        if($campaignCarouselID >= 1){
            $carousel = Carousel::where('id', $campaignCarouselID)->first();

            if($carousel){
                $carousel->has_campaign = 1;
                $carousel->save();
            }
        }
    }

    /**
     * Handle the Campaign "updated" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function updated(Campaign $campaign)
    {
        $campaignCarouselID = $campaign->carousel_id;

        // if campaign have carousel, cannot be 0 or null
        if($campaignCarouselID >= 1){
            $carousel = Carousel::where('id', $campaignCarouselID)->first();

            if($carousel){
                $carousel->has_campaign = 1;
                $carousel->save();
            }
        }
    }

    /**
     * Handle the Campaign "deleted" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function deleted(Campaign $campaign)
    {
        $campaignCarouselID = $campaign->carousel_id;

        // if campaign have carousel, cannot be 0 or null
        if($campaignCarouselID >= 1){
            $carousel = Carousel::where('id', $campaignCarouselID)->first();

            if($carousel){
                $carousel->has_campaign = 0;
                $carousel->save();
            }
        }
    }

    /**
     * Handle the Campaign "restored" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function restored(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the Campaign "force deleted" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function forceDeleted(Campaign $campaign)
    {
        //
    }
}
