<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Region;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Tag;
use Illuminate\Support\Collection;

class FilterController extends Controller
{
    public function getAllFilters(Request $req){
        if($req->has('category')){
            switch($req->category){
                case 'region':
                    //$regions = Region::select('id', 'region_display_name', 'region_name')->get();

                    //$sortedRegions = Region::select('id', 'region_display_name', 'region_name')->where('region_name', '!=', 'AllRegions')->orderBy('region_display_name')->get();
                    $regions = Region::select('id', 'region_display_name', 'region_name')->orderBy('region_display_name')->get();
                    // $regions = Region::select('id', 'region_display_name', 'region_name')->where('id', '=', 1)->get();
                    $allRegionsData = Collection::make(['id'=>1, 'region_display_name' => 'All Regions', 'region_name' => 'AllRegions']);

                    // put all region to the first row 
                    $regions->prepend($allRegionsData);

                    // if($sortedRegions){
                    //     foreach($sortedRegions as $region){
                    //         //$regions->push($region);
                    //         $regions->id->push($region->id);
                    //     }
                    // }

                    //if(count($regions) >= 1){
                    if($regions){
                        return response()->json($regions, 200);
                    }
                    else {
                        return response()->json('No regions are available at the moment', 202);
                    }
                    break;
                case 'country':
                    //$countries = Country::select('id', 'country_display_name', 'country_name')->get();

                    //$sortedCountries = Country::select('id', 'country_display_name', 'country_name')->where('country_name', '!=', 'AllCountries')->orderBy('country_display_name')->get();

                    $countries = Country::select('id', 'country_display_name', 'country_name')->orderBy('country_display_name')->get();

                    // if($sortedCountries){
                    //     foreach($sortedCountries as $country){
                    //         $countries->push($country);
                    //     }
                    // }

                    $allCountriesData = Collection::make(['id'=>1, 'country_display_name' => 'All Countries', 'country_name' => 'AllCountries']);
                    $countries->prepend($allCountriesData);

                    //if(count($countries) >= 1){
                    if($countries){
                        return response()->json($countries, 200);
                    }
                    else {
                        return response()->json('No countries are available at the moment', 202);
                    }
                    break;
                case 'genre':
                    $genres = Genre::select('id', 'genre_display_name', 'genre_name')->orderBy('genre_display_name')->get();

                    $allGenresData = Collection::make(['id'=>1, 'genre_display_name' => 'All Genres', 'genre_name' => 'AllGenres']);
                    $genres->prepend($allGenresData);
                    
                    if($genres){
                        return response()->json($genres, 200);
                    }
                    else {
                        return response()->json('No genres are available at the moment', 202);
                    }
                    break;
                case 'language':
                    //$sortedLanguages = Language::select('id', 'language_display_name', 'language_name')->where('language_name', '!=', 'AllLanguages')->orderBy('language_display_name')->get();

                    $languages = Language::select('id', 'language_display_name', 'language_name')->orderBy('language_display_name')->get();

                    $allLanguagesData = Collection::make(['id'=>1, 'language_display_name' => 'All Languages', 'language_name' => 'AllLanguages']);
                    $languages->prepend($allLanguagesData);

                    if($languages){
                        return response()->json($languages, 200);
                    }
                    else {
                        return response()->json('No languages are available at the moment', 202);
                    }
                    break;
                case 'tag':
                        $tags = Tag::select('id', 'tag_display_name', 'tag_name')->orderBy('tag_display_name')->get();
    
                        if(count($tags) >= 1){
                            return response()->json($tags, 200);
                        }
                        else {
                            return response()->json('No tags are available at the moment', 202);
                        }
                        break;
                default:
                    return response()->json('This filter category is invalid', 202);
            }
        }
    }
}
