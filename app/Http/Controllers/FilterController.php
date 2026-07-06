<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Region;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Show;
use App\Models\ShowType;
use App\Models\Tag;
use Illuminate\Support\Collection;

class FilterController extends Controller
{
    /**
     * Resolve the show_type_id from the show_type parameter.
     * Accepts: "Films", "Series", "Shorts", "Documentaries" (case-insensitive)
     */
    private function resolveShowTypeId(Request $req)
    {
        if (!$req->has('show_type')) {
            return null;
        }

        $showType = ShowType::where('show_type', $req->show_type)->first();
        return $showType ? $showType->id : null;
    }

    public function getAllFilters(Request $req){
        if($req->has('category')){
            $showTypeId = $this->resolveShowTypeId($req);

            switch($req->category){
                case 'region':
                    if ($showTypeId) {
                        // Only return regions that have at least one published show of this type
                        $regions = Region::select('regions.id', 'regions.region_display_name', 'regions.region_name')
                            ->whereHas('shows', function ($query) use ($showTypeId) {
                                $query->where('show_type_id', $showTypeId)
                                      ->where('is_publish', true);
                            })
                            ->orderBy('region_display_name')
                            ->get();
                    } else {
                        $regions = Region::select('id', 'region_display_name', 'region_name')->orderBy('region_display_name')->get();
                    }

                    $allRegionsData = Collection::make(['id'=>1, 'region_display_name' => 'All Regions', 'region_name' => 'AllRegions']);
                    $regions->prepend($allRegionsData);

                    if($regions){
                        return response()->json($regions, 200);
                    }
                    else {
                        return response()->json('No regions are available at the moment', 202);
                    }
                    break;
                case 'country':
                    if ($showTypeId) {
                        // Only return countries that have at least one published show of this type
                        $countries = Country::select('countries.id', 'countries.country_display_name', 'countries.country_name')
                            ->whereHas('shows', function ($query) use ($showTypeId) {
                                $query->where('show_type_id', $showTypeId)
                                      ->where('is_publish', true);
                            })
                            ->orderBy('country_display_name')
                            ->get();
                    } else {
                        $countries = Country::select('id', 'country_display_name', 'country_name')->orderBy('country_display_name')->get();
                    }

                    $allCountriesData = Collection::make(['id'=>1, 'country_display_name' => 'All Countries', 'country_name' => 'AllCountries']);
                    $countries->prepend($allCountriesData);

                    if($countries){
                        return response()->json($countries, 200);
                    }
                    else {
                        return response()->json('No countries are available at the moment', 202);
                    }
                    break;
                case 'genre':
                    if ($showTypeId) {
                        // Only return genres that have at least one published show of this type
                        $genres = Genre::select('genres.id', 'genres.genre_display_name', 'genres.genre_name')
                            ->whereHas('films', function ($query) use ($showTypeId) {
                                $query->where('show_type_id', $showTypeId)
                                      ->where('is_publish', true);
                            })
                            ->orderBy('genre_display_name')
                            ->get();
                    } else {
                        $genres = Genre::select('id', 'genre_display_name', 'genre_name')->orderBy('genre_display_name')->get();
                    }

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
                    if ($showTypeId) {
                        // Only return languages that have at least one published show of this type
                        $languages = Language::select('languages.id', 'languages.language_display_name', 'languages.language_name')
                            ->whereHas('shows', function ($query) use ($showTypeId) {
                                $query->where('show_type_id', $showTypeId)
                                      ->where('is_publish', true);
                            })
                            ->orderBy('language_display_name')
                            ->get();
                    } else {
                        $languages = Language::select('id', 'language_display_name', 'language_name')->orderBy('language_display_name')->get();
                    }

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
