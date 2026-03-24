<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscribePartner;
use App\Models\Country;

class SubscribePartnerController extends Controller
{
    public function getSubscribePartners(Request $req){
        $data = [];
        //$countries = Country::All();
        $partnerCountriesId = SubscribePartner::pluck('country_id');
        
        $partnerCountryList = Country::whereIn('id', $partnerCountriesId)->select('id', 'country_display_name', 'country_name')->orderBy('country_display_name')->get();
        
        if($partnerCountryList){
            foreach($partnerCountryList as $indexPos => $country){
                $data['countryList'][$indexPos]['id'] = $country->id;
                $data['countryList'][$indexPos]['country_name'] = $country->country_name;
                $data['countryList'][$indexPos]['country_display_name'] = $country->country_display_name;
            }
        }
        
        $subscribePartnerModel = (new SubscribePartner)->newQuery();
        if($req->has('type')){
            switch($req->type){
                case 'paytv':
                    $subscribePartnerModel->where('pay_tv', true);
                    break;
                case 'streaming':
                    $subscribePartnerModel->where('streaming', true);
                    break;
                default:
                    return response()->json('Invalid type', 202);
            }
        }

        if($req->has('country')){
            $country = $partnerCountryList->where('country_name', $req->country)->first();
            if($country){
                $subscribePartnerModel->where('country_id', $country->id);
            }
        }
            
        $subscribePartners = $subscribePartnerModel->select('id', 'partner', 'country_id', 'link_url', 'pay_tv', 'streaming', 'logo_path')->orderBy('partner')->get();

        if($subscribePartners){
            foreach($subscribePartners as $indexPos => $subscribePartner){
                $data['partners'][$indexPos]['id'] = $subscribePartner->id;
                $data['partners'][$indexPos]['partner'] = $subscribePartner->partner;
                //$country = $countries->where('id', $subscribePartner->country_id)->first();
                $country = $partnerCountryList->where('id', $subscribePartner->country_id)->first();
                $data['partners'][$indexPos]['country'] = $country->country_display_name;
                $data['partners'][$indexPos]['link_url'] = $subscribePartner->link_url;
                $data['partners'][$indexPos]['pay_tv'] = $subscribePartner->pay_tv;
                $data['partners'][$indexPos]['streaming'] = $subscribePartner->streaming;
                $data['partners'][$indexPos]['logo_path'] = $subscribePartner->logo_path;
            }

            return response()->json($data, 200);
        }
        else {
            return response()->json('No subscribe partner were found', 202);
        }
    }
}
