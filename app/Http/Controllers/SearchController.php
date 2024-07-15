<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Show;
use App\Models\Instalment;
use App\Models\ShowType;
use App\Models\Region;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Cast;
use App\Models\Director;

use MeiliSearch\Endpoints\Indexes;

class SearchController extends Controller
{
    public function search(Request $req){
        $searchStr = $req->q;

        if($searchStr){

            $results = [];

            $ifMatchIsFound = false;

            $showsResults = Show::search($searchStr, function (Indexes $meilisearch, $queryStr, $options){
                // for searchable attributes
                $options['attributesToRetrieve'] = ['id', 'title'];

                // filter
                $meilisearch->updateFilterableAttributes(['is_publish']);
                $options['filter'] = "is_publish = true OR is_publish = 1";

                // search in the title column
                $meilisearch->updateSearchableAttributes(['title', 'foreign_title']);

                // return response as the specified attributes in the object
                // $meilisearch->updateDisplayedAttributes(['id', 'title', 'slug', 'show_type_id', 'trailer_link_url']);

                return $meilisearch->search($queryStr, $options);

            })->get();

            $instalmentResults = Instalment::search($searchStr, function (Indexes $meilisearch, $queryStr, $options){
                // for searchable attributes
                $options['attributesToRetrieve'] = ['id', 'title'];

                // filter
                $meilisearch->updateFilterableAttributes(['is_publish']);
                $options['filter'] = "is_publish = true";

                // search in the title column
                $meilisearch->updateSearchableAttributes(['title', 'foreign_title']);

                // return response as the specified attributes in the object
                // $meilisearch->updateDisplayedAttributes(['id', 'title', 'slug', 'show_type_id', 'trailer_link_url']);

                return $meilisearch->search($queryStr, $options);

            })->get();

            $castResults = Cast::search($searchStr, function (Indexes $meilisearch, $queryStr, $options){
                // for searchable attributes
                $options['attributesToRetrieve'] = ['id', 'name'];

                $meilisearch->updateSearchableAttributes(['name']);

                return $meilisearch->search($queryStr, $options);

            })->get();

            $directorResults = Director::search($searchStr, function (Indexes $meilisearch, $queryStr, $options){
                // for searchable attributes
                $options['attributesToRetrieve'] = ['id', 'name'];

                $meilisearch->updateSearchableAttributes(['name']);

                return $meilisearch->search($queryStr, $options);

            })->get();
    
            if(count($showsResults) >= 1){
    
                $ifMatchIsFound = true;

                foreach($showsResults as $indexPos => $showResult){

                    if($showResult->cover_photo_path){
                        if(Storage::disk('public')->exists($showResult->cover_photo_path)){
                            $showResult->cover_photo_path = Storage::url($showResult->cover_photo_path);
                        }
                        else {
                            $showResult->cover_photo_path = null;
                        }
                    }

                    $results['shows'][$indexPos]['id'] = $showResult->id;
                    $results['shows'][$indexPos]['title'] = $showResult->title;
                    $results['shows'][$indexPos]['foreign_title'] = $showResult->foreign_title;
                    $results['shows'][$indexPos]['show_type'] = ShowType::where('id', $showResult->show_type_id)->value('show_type');
                    $results['shows'][$indexPos]['trailer_link_url'] = $showResult->trailer_link_url;
                    $results['shows'][$indexPos]['cover_photo_path'] = $showResult->cover_photo_path;
                    $results['shows'][$indexPos]['is_new'] = $showResult->is_new;

                    switch($showResult->show_type_id){
                        case 1:
                            $results['shows'][$indexPos]['slug'] = '/films/'.$showResult->slug;
                            break;
                        case 2:
                            $results['shows'][$indexPos]['slug'] = '/series/'.$showResult->slug;
                            break;
                        case 3:
                            $results['shows'][$indexPos]['slug'] = '/docufilms/'.$showResult->slug;
                            break;
                        case 4:
                            $results['shows'][$indexPos]['slug'] = '/shorts/'.$showResult->slug;
                            break;
                    }
                }

            }

            if(count($instalmentResults) >= 1){
    
                $ifMatchIsFound = true;

                foreach($instalmentResults as $indexPos => $instalmentResult){

                    if($instalmentResult->cover_photo_path){
                        if(Storage::disk('public')->exists($instalmentResult->cover_photo_path)){
                            $instalmentResult->cover_photo_path = Storage::url($instalmentResult->cover_photo_path);
                        }
                        else {
                            $instalmentResult->cover_photo_path = null;
                        }
                    }

                    $results['instalments'][$indexPos]['id'] = $instalmentResult->id;
                    $results['instalments'][$indexPos]['title'] = $instalmentResult->series->title.': '.$instalmentResult->title;
                    $results['instalments'][$indexPos]['foreign_title'] = $instalmentResult->series->title.': '.$instalmentResult->foreign_title;
                    $results['instalments'][$indexPos]['slug'] = '/series/'.$instalmentResult->series->slug.'/'.$instalmentResult->slug;
                    $results['instalments'][$indexPos]['show_type'] = ShowType::where('id', $instalmentResult->series->show_type_id)->value('show_type');
                    $results['instalments'][$indexPos]['trailer_link_url'] = $instalmentResult->trailer_link_url;
                    $results['instalments'][$indexPos]['cover_photo_path'] = $instalmentResult->cover_photo_path;
                    $results['instalments'][$indexPos]['is_new'] = $instalmentResult->is_new;
                }

            }

            if(count($castResults) >= 1){
    
                $ifMatchIsFound = true;

                foreach($castResults as $indexPos => $castResult){

                    if($castResult->profile_photo_path){
                        if(Storage::disk('public')->exists($castResult->profile_photo_path)){
                            $castResult->profile_photo_path = Storage::url($castResult->profile_photo_path);
                        }
                        else {
                            $castResult->profile_photo_path = null;
                        }
                    }

                    $results['cast'][$indexPos]['id'] = $castResult->id;
                    $results['cast'][$indexPos]['name'] = $castResult->name;
                    $results['cast'][$indexPos]['slug'] = '/casts/'.$castResult->slug;
                    $results['cast'][$indexPos]['profile_photo_path'] = $castResult->profile_photo_path;
                }
            }

            if(count($directorResults) >= 1){
    
                $ifMatchIsFound = true;

                foreach($directorResults as $indexPos => $directorResult){

                    if($directorResult->profile_photo_path){
                        if(Storage::disk('public')->exists($directorResult->profile_photo_path)){
                            $directorResult->profile_photo_path = Storage::url($directorResult->profile_photo_path);
                        }
                        else {
                            $directorResult->profile_photo_path = null;
                        }
                    }

                    $results['directors'][$indexPos]['id'] = $directorResult->id;
                    $results['directors'][$indexPos]['name'] = $directorResult->name;
                    $results['directors'][$indexPos]['slug'] = '/directors/'.$directorResult->slug;
                    $results['directors'][$indexPos]['profile_photo_path'] = $directorResult->profile_photo_path;
                }
            }

            else if(!($ifMatchIsFound)){
                return response()->json('No results were found that matches your search', 202);
            }

            return response()->json($results, 200);
        }
        else {
            return response()->json('Please type something in the search box', 202);
        }
    }
}
