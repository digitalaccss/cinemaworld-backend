<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SiteController extends Controller
{
    public function getSetting(Request $req, $settingID){
        $setting = Setting::select('id', 'site_title', 'site_address_url', 'meta_title', 'meta_description', 'page_header', 'page_description')
        ->where('id', $settingID)
        ->first();

        $data = [];

        if($setting){
            $data['id'] = $setting->id;
            $data['site_title'] = $setting->site_title;
            $data['site_address_url'] = $setting->site_address_url;
            $data['meta_title'] = $setting->meta_title;
            $data['meta_description'] = $setting->meta_description;
            $data['page_header'] = $setting->page_header;
            $data['page_description'] = $setting->page_description;

            return response()->json($data, 200);
        }else{
            return response()->json('No setting were found', 202);
        }
    }
}
