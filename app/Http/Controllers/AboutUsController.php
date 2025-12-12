<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AboutUs;

class AboutUsController extends Controller
{
    public function getAboutUs(Request $req){
        $aboutUs = AboutUs::select('id', 'content', 'title', 'banner_type', 'sub_title', 'video_link_url', 'about_us_banner_path', 'button_text', 'button_link_url')
        ->first();

        if($aboutUs){
            $data = [];

            $data['id'] = $aboutUs->id;
            $data['title'] = $aboutUs->title;
            if($aboutUs->banner_type){
                if($aboutUs->banner_type == "image"){
                    $data['banner_type'] = $aboutUs->banner_type;
                    if($aboutUs->about_us_banner_path){
                        if(Storage::disk('public')->exists($aboutUs->about_us_banner_path)){
                            $data['about_us_banner_path'] = Storage::url($aboutUs->about_us_banner_path);
                        }
                        else {
                            $data['about_us_banner_path'] = null;
                        }
                    }
                }else if($aboutUs->banner_type == "video"){
                    $data['banner_type'] = $aboutUs->banner_type;
                    $data['video_link_url'] = $aboutUs->video_link_url;
                }

            }
            $data['sub_title'] = $aboutUs->sub_title;
            $data['content'] = $aboutUs->content;
            $data['button_text'] = $aboutUs->button_text;
            $data['button_link_url'] = $aboutUs->button_link_url;

            return response()->json($data, 200);
        }else{
            return response()->json('About Us is unavailable at the moment', 202);
        }
    }
}
