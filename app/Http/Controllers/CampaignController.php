<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Carousel;
use App\Models\Show;
use App\Models\ShowType;
use App\Models\Campaign;

class CampaignController extends Controller
{
    public function getAllCampaigns(Request $req){
        $campaigns = Campaign::select('id', 'title', 'slogan', 'slug', 'content', 'facebook_link_url', 'banner_path', 'carousel_id', 'banner_alt')
        ->get();

        if(count($campaigns) >= 1){
            $data = [];
            foreach($campaigns as $indexPos => $campaign){
                $data[$indexPos]['id'] = $campaign->id;
                $data[$indexPos]['title'] = $campaign->title;
                $data[$indexPos]['slogan'] = $campaign->slogan;
                $data[$indexPos]['slug'] = '/campaigns/'.$campaign->slug;
                $data[$indexPos]['content'] = $campaign->content;
                $data[$indexPos]['facebook_link_url'] = $campaign->facebook_link_url;
                $data[$indexPos]['banner_alt'] = $campaign->banner_alt;
                if($campaign->banner_path){
                    if(Storage::disk('public')->exists($campaign->banner_path)){
                        $data['banner_path'] = Storage::url($campaign->banner_path);
                    }
                    else {
                        $data['banner_path'] = null;
                    }
                }

                $carouselShowIDs = Carousel::where('id', $campaign->carousel_id)->value('shows');

                foreach($carouselShowIDs as $carouselShowID){
                    $show = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new')
                    ->where('id', $carouselShowID)
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

                        $data['shows'][][] = $show;
                    }
                
                }
            }

            return response()->json($data, 200);
        }
        else {
            return response()->json('No campaign was found', 202);
        }
    }

    public function getCampaign(Request $req){
        $campaign = Campaign::select('id', 'title', 'slogan', 'slug', 'content', 'facebook_link_url', 'banner_path', 'carousel_id', 'banner_alt')
        ->where('slug', $req->slug)
        ->first();

        if($campaign){
            $data = [];

            $data['id'] = $campaign->id;
            $data['title'] = $campaign->title;
            $data['slogan'] = $campaign->slogan;
            $data['slug'] = '/campaigns/' . $campaign->slug;
            $data['content'] = $campaign->content;
            $data['facebook_link_url'] = $campaign->facebook_link_url;
            $data['banner_alt'] = $campaign->banner_alt;
            if($campaign->banner_path){
                if(Storage::disk('public')->exists($campaign->banner_path)){
                    $data['banner_path'] = Storage::url($campaign->banner_path);
                }
                else {
                    $data['banner_path'] = null;
                }
            }

            $carouselShowIDs = Carousel::where('id', $campaign->carousel_id)->value('shows');

            foreach($carouselShowIDs as $carouselShowID){
                $show = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new')
                ->where('id', $carouselShowID)
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
            
            return response()->json($data, 200);
        }
        else {
            return response()->json('This campaign was not found', 202);
        }
    }
}
