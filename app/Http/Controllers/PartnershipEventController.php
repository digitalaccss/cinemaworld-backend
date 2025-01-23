<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\PartnershipEvent;
use App\Models\PartnershipEventType;

class PartnershipEventController extends Controller
{
    public function getAllPartnershipEvents(Request $req){
        $partnershipEventModel = (new PartnershipEvent)->newQuery();

        if($req->has('type')){
            switch($req->type){
                case 'partnership':
                    $partnershipEventModel->where('partnership_event_type_id', 1);
                    break;
                case 'events':
                    $partnershipEventModel->where('partnership_event_type_id', 2);
                    break;
                case 'archive':
                    $partnershipEventModel->where('is_archived', 1);
                    break;
                case 'blog':
                    $partnershipEventModel->where('partnership_event_type_id', 3);
                    break;
                default:
                    return response()->json('This partnership and event filter type is invalid', 202);
            }
        }

        $partnershipEvents = $partnershipEventModel->select('id', 'title', 'slug', 'partnership_event_type_id', 'cover_photo_path', 'created_at')->get();

        if(count($partnershipEvents) >= 1){
            $data = [];

            foreach($partnershipEvents as $indexPos => $partnershipEvent){
                $data[$indexPos]['id'] = $partnershipEvent->id;
                $data[$indexPos]['title'] = $partnershipEvent->title;
                $data[$indexPos]['slug'] = '/partnership-events/'.$partnershipEvent->slug;
                $data[$indexPos]['partnership_event_type'] = PartnershipEventType::where('id', $partnershipEvent->partnership_event_type_id)->value('partnership_event_type');
                
                if($partnershipEvent->cover_photo_path){
                    if(Storage::disk('public')->exists($partnershipEvent->cover_photo_path)){
                        $partnershipEvent->cover_photo_path = Storage::url($partnershipEvent->cover_photo_path);
                    }
                    else {
                        $partnershipEvent->cover_photo_path = null;
                    }
                    $data[$indexPos]['cover_photo_path'] = $partnershipEvent->cover_photo_path;
                }

                $data[$indexPos]['published_at'] = date('d M Y', strtotime($partnershipEvent->created_at));
            }

            return response()->json($data, 200);
        }
        else {
            return response()->json('No partnership and events were found that matches your filter', 202);
        }
    }

    public function getPartnershipEvent(Request $req){
        $partnershipEvent = PartnershipEvent::select('id', 'title', 'slogan', 'slug', 'partnership_event_type_id', 'content', 'facebook_link_url', 'banner_path', 'created_at')
        ->where('slug', $req->slug)
        ->first();

        if($partnershipEvent){
            $data = [];

            $data['id'] = $partnershipEvent->id;
            $data['title'] = $partnershipEvent->title;
            $data['slogan'] = $partnershipEvent->title;
            $data['slug'] = '/partnership-events/'.$partnershipEvent->slug;
            $data['partnership_event_type'] = PartnershipEventType::where('id', $partnershipEvent->partnership_event_type_id)->value('partnership_event_type');
            $data['content'] = $partnershipEvent->content;
            $data['facebook_link_url'] = $partnershipEvent->facebook_link_url;

            if($partnershipEvent->banner_path){
                if(Storage::disk('public')->exists($partnershipEvent->banner_path)){
                    $partnershipEvent->banner_path = Storage::url($partnershipEvent->banner_path);
                }
                else {
                    $partnershipEvent->banner_path = null;
                }
                $data['banner_path'] = $partnershipEvent->banner_path;
            }

            $timestamp = strtotime($partnershipEvent->created_at);

            $data['published_at_date'] = date('d M Y', $timestamp);
            $data['published_at_time'] = date('H:i a', $timestamp);

            return response()->json($data, 200);
        }
        else {
            return response()->json('This partnership or event was not found', 202);
        }
    }
}
