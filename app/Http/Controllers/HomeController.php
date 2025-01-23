<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Home;
use App\Models\Carousel;
use App\Models\Show;
use App\Models\Instalment;
use App\Models\ShowType;
use App\Models\Campaign;
use App\Models\Schedule;
// use App\Models\PartnershipEvent;
// use App\Models\PartnershipEventType;
use App\Models\Blog;
use App\Models\BlogType;

class HomeController extends Controller
{
    public function getHomepage(Request $req){
        $homeFeaturedShow = Home::with('featuredShow:id,title,foreign_title,slug,show_type_id,runtime,short_desc,trailer_link_url,is_new')->first();

        if($homeFeaturedShow){

            $data = [];

            $data['id'] = $homeFeaturedShow->id;
            $data['active_carousels'] = $homeFeaturedShow->active_carousels;

            if($homeFeaturedShow->featured_banner_path){
                if(Storage::disk('public')->exists($homeFeaturedShow->featured_banner_path)){
                    $homeFeaturedShow->featured_banner_path = Storage::url($homeFeaturedShow->featured_banner_path);
                }
                else {
                    $homeFeaturedShow->featured_banner_path = null;
                }
                $data['featured_banner_path'] = $homeFeaturedShow->featured_banner_path;
            }else{
                $data['featured_banner_path'] = null;
            }

            if($homeFeaturedShow->mobile_featured_banner_path){
                if(Storage::disk('public')->exists($homeFeaturedShow->mobile_featured_banner_path)){
                    $homeFeaturedShow->mobile_featured_banner_path = Storage::url($homeFeaturedShow->mobile_featured_banner_path);
                }
                else {
                    $homeFeaturedShow->mobile_featured_banner_path = null;
                }
                $data['mobile_featured_banner_path'] = $homeFeaturedShow->mobile_featured_banner_path;
            }else{
                $data['mobile_featured_banner_path'] = null;
            }

            $data['featured_show']['id'] = $homeFeaturedShow->featuredShow->id;
            $data['featured_show']['title'] = $homeFeaturedShow->featuredShow->title;
            $data['featured_show']['foreign_title'] = $homeFeaturedShow->featuredShow->foreign_title;
            $data['featured_show']['runtime'] = $homeFeaturedShow->featuredShow->runtime;
            $data['featured_show']['short_desc'] = $homeFeaturedShow->featuredShow->short_desc;
            $data['featured_show']['trailer_link_url'] = $homeFeaturedShow->featuredShow->trailer_link_url;
            $data['featured_show']['is_new'] = $homeFeaturedShow->featuredShow->is_new;

            switch($homeFeaturedShow->featuredShow->show_type_id){
                case 1:
                    $data['featured_show']['slug'] = '/films/'.$homeFeaturedShow->featuredShow->slug;
                    break;
                case 2:
                    $data['featured_show']['slug'] = '/series/'.$homeFeaturedShow->featuredShow->slug;
                    break;
                case 2:
                    $data['featured_show']['slug'] = '/docufilms/'.$homeFeaturedShow->featuredShow->slug;
                    break;
                case 4:
                    $data['featured_show']['slug'] = '/shorts/'.$homeFeaturedShow->featuredShow->slug;
                    break;
            }

            $currentDateStr = date('Y-m-d');
            $currentTimeStr = date('H:i:s');
        
            $tonightShow = Schedule::select('id', 'schedule_start_date', 'schedule_start_time', 'show_id', 'instalment_id')
            ->whereDate('schedule_start_date', $currentDateStr)
            ->whereDate('schedule_end_date', $currentDateStr)
            ->whereTime('schedule_start_time', '21:00:00')
            ->first();
            // ->where(function ($query) {
            //     $query->whereTime('schedule_start_time', '21:00:00')
            //         ->orWhere('schedule_start_time', '22:00:00');
            // })
            //->whereTime('schedule_start_time', '21:00:00')
            //->whereTime('schedule_end_time', '>', $currentTimeStr)

            if($tonightShow){
                $show = Show::select('id', 'title', 'foreign_title', 'runtime', 'trailer_link_url', 'leaderboard_banner_path', 'is_new', 'is_publish', 'web_tonight_banner_photo_path', 'mobile_tonight_banner_photo_path')
                ->where('id', $tonightShow->show_id)
                ->where('is_publish', true)
                ->first();

                if($show){
                    if($tonightShow->instalment_id){
                        $instalment = Instalment::select('title', 'foreign_title', 'runtime', 'trailer_link_url', 'leaderboard_banner_path', 'is_new', 'is_publish', 'web_tonight_banner_photo_path', 'mobile_tonight_banner_photo_path')
                        ->where('series_id', $show->id)
                        ->where('id', $tonightShow->instalment_id)
                        ->where('is_publish', true)
                        ->first();
    
                        if($instalment){
                            $show->title = $show->title.' - '.$instalment->title;
                            $show->foreign_title = $show->foreign_title.' - '.$instalment->foreign_title;
                            $show->runtime = $instalment->runtime;
                            $show->trailer_link_url = $instalment->trailer_link_url;
                            $show->leaderboard_banner_path = $instalment->leaderboard_banner_path;
                            $show->is_new = $instalment->is_new;
                            $show->web_tonight_banner_photo_path = $instalment->web_tonight_banner_photo_path;
                            $show->mobile_tonight_banner_photo_path = $instalment->mobile_tonight_banner_photo_path;
                            //$show->web_tonight_banner_photo_path = $instalment->web_tonight_banner_photo_path == null? null : $instalment->web_tonight_banner_photo_path;
                            //$show->mobile_tonight_banner_photo_path = $instalment->mobile_tonight_banner_photo_path == null? null : $instalment->mobile_tonight_banner_photo_path;
                        }
                    }

                    if($show->leaderboard_banner_path){
                        if(Storage::disk('public')->exists($show->leaderboard_banner_path)){
                            $show->leaderboard_banner_path = Storage::url($show->leaderboard_banner_path);
                        }
                        else {
                            $show->leaderboard_banner_path = null;
                        }
                    }

                    if($show->web_tonight_banner_photo_path){
                        if(Storage::disk('public')->exists($show->web_tonight_banner_photo_path)){
                            $show->web_tonight_banner_photo_path = Storage::url($show->web_tonight_banner_photo_path);
                        }
                        else {
                            $show->web_tonight_banner_photo_path = null;
                        }
                    }

                    if($show->mobile_tonight_banner_photo_path){
                        if(Storage::disk('public')->exists($show->mobile_tonight_banner_photo_path)){
                            $show->mobile_tonight_banner_photo_path = Storage::url($show->mobile_tonight_banner_photo_path);
                        }
                        else {
                            $show->mobile_tonight_banner_photo_path = null;
                        }
                    }

                    $show->schedule_start_date = date('Y-m-d', strtotime($tonightShow->schedule_start_date));
                    $show->schedule_start_time = date('g:i A', strtotime($tonightShow->schedule_start_time));

                    $data['tonight_show'] = $show;
                }
            }

            $customizeBanner = Home::select('customize_banner_display_text', 'customize_banner_button_text', 'customize_banner_button_link_url', 'customize_banner_background_colour', 'customize_banner_image_path', 'customize_banner_button_colour', 'customize_banner_text_colour', 'customize_banner_button_text_colour')
            ->where('is_publish_customize_banner', true)
            ->first();

            if($customizeBanner){
                $data['customize_banner'] = $customizeBanner;
            }

            $popup = Home::select('is_show_popup', 'is_popup_open_in_new_tab', 'popup_type', 'popup_image_path', 'popup_video_link', 'popup_external_link', 'popup_video_button_text', 'popup_video_button_text_colour', 'popup_video_button_colour')
            ->where('is_show_popup', true)
            ->first();

            if($popup){
                if($popup->popup_type == 'video'){
                  $popupData = $popup->only(['is_show_popup', 'is_popup_open_in_new_tab', 'popup_type', 'popup_video_link', 'popup_external_link', 'popup_video_button_text', 'popup_video_button_text_colour', 'popup_video_button_colour']);
                }else if($popup->popup_type == 'image/gif'){
                  $popupData = $popup->only(['is_show_popup', 'is_popup_open_in_new_tab', 'popup_type', 'popup_image_path', 'popup_external_link']);
                }
                $data['popup'] = $popupData;
            }

            return response()->json($data, 200);
        }
        else {
            return response()->json('Homepage is unavailable at the moment', 202);
        }
        
    }

    public function getCarouselShows(Request $req, $carouselID){
        $instalments = Instalment::all();
        $carousel = Carousel::select('id', 'carousel_display_name', 'shows')->where('id', $carouselID)->first();

        if($carousel){
            $data = [];

            $data['id'] = $carousel->id;
            $data['carousel_display_name'] = $carousel->carousel_display_name;

            $campaignSlug = Campaign::where('carousel_id', $carousel->id)->value('slug');

            if($campaignSlug){
                $data['campaign_slug'] = '/campaigns/'.$campaignSlug;
            }

            $showIDs = $carousel->shows;
            
            if($showIDs){
                foreach($showIDs as $showID){
                    $show = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new')
                    ->where('id', $showID)
                    ->where('is_publish', true)
                    ->first();

                    if($show){
                    $show->show_type = ShowType::where('id', $show->show_type_id)->value('show_type');
                    
                        switch($show->show_type_id){
                            case 1:
                                $show->slug = '/films/'.$show->slug;
                                break;
                            case 2:
                                $show->slug = '/series/'.$show->slug;
                                $newInstalment = $instalments->where('series_id', $show->id)->where('is_publish', true)->where('is_new', true)
                                ->first();
                                if($newInstalment){
                                    $show->new_instalment = true;
                                }
                                break;
                            case 3:
                                $show->slug = '/docufilms/'.$show->slug;
                                break;
                            case 4:
                                $show->slug = '/shorts/'.$show->slug;
                                break;
                        }

                        if($show->cover_photo_path){
                            if(Storage::disk('public')->exists($show->cover_photo_path)){
                                $show->cover_photo_path = Storage::url($show->cover_photo_path);
                            }
                            else {
                                $show->cover_photo_path = null;
                            }
                        }
    
                        unset($show->show_type_id);
    
                        $data['shows'][] = $show;
                        
                    }
                }
            }

            return response()->json($data, 200);
        }
        else {
            return response()->json('No shows are available in this carousel at the moment', 202);
        }
    }

    public function getLatestBlogs(Request $req){
        $blogs = Blog::select('id', 'title', 'slug', 'blog_type_id', 'cover_photo_path', 'created_at')->orderBy('created_at', 'desc')->limit(6)->get();

        if(count($blogs) >= 1){
            $data = [];

            foreach($blogs as $indexPos => $blog){
                $data[$indexPos]['id'] = $blog->id;
                $data[$indexPos]['title'] = $blog->title;
                $data[$indexPos]['slug'] = '/blogs/'.$blog->slug;
                $data[$indexPos]['blog_type'] = BlogType::where('id', $blog->blog_type_id)->value('blog_type');

                if($blog->cover_photo_path){
                    if(Storage::disk('public')->exists($blog->cover_photo_path)){
                        $blog->cover_photo_path = Storage::url($blog->cover_photo_path);
                    }
                    else {
                        $blog->cover_photo_path = null;
                    }
                    $data[$indexPos]['cover_photo_path'] = $blog->cover_photo_path;
                }

                $data[$indexPos]['published_at'] = date('d M Y', strtotime($blog->created_at));
            }

            return response()->json($data, 200);
        }
        else {
            return response()->json('No latest blogs are available at the moment', 202);
        }
    }

    // public function getLatestPartnershipEvents(Request $req){
    //     $partnershipEvents = PartnershipEvent::select('id', 'title', 'slug', 'partnership_event_type_id', 'cover_photo_path', 'created_at')->orderBy('created_at', 'desc')->limit(3)->get();

    //     if(count($partnershipEvents) >= 1){
    //         $data = [];

    //         foreach($partnershipEvents as $indexPos => $partnershipEvent){
    //             $data[$indexPos]['id'] = $partnershipEvent->id;
    //             $data[$indexPos]['title'] = $partnershipEvent->title;
    //             $data[$indexPos]['slug'] = '/partnership-events/'.$partnershipEvent->slug;
    //             $data[$indexPos]['partnership_event_type'] = PartnershipEventType::where('id', $partnershipEvent->partnership_event_type_id)->value('partnership_event_type');

    //             if($partnershipEvent->cover_photo_path){
    //                 if(Storage::disk('public')->exists($partnershipEvent->cover_photo_path)){
    //                     $partnershipEvent->cover_photo_path = Storage::url($partnershipEvent->cover_photo_path);
    //                 }
    //                 else {
    //                     $partnershipEvent->cover_photo_path = null;
    //                 }
    //                 $data[$indexPos]['cover_photo_path'] = $partnershipEvent->cover_photo_path;
    //             }

    //             $data[$indexPos]['published_at'] = date('d M Y', strtotime($partnershipEvent->created_at));
    //         }

    //         return response()->json($data, 200);
    //     }
    //     else {
    //         return response()->json('No latest partnership and events are available at the moment', 202);
    //     }
    // }
}
