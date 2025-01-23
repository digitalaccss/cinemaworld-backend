<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;

use App\Models\Show;
use App\Models\ShowType;
use App\Models\Instalment;
use App\Models\Genre;
use App\Models\ShowGenre;
use App\Models\Region;
use App\Models\Country;
use App\Models\ShowCountry;
use App\Models\Language;
use App\Models\ShowLanguage;
use App\Models\InstalmentLanguage;
use App\Models\Accolade;
use App\Models\ShowAccolade;
use App\Models\Critic;
use App\Models\Director;
use App\Models\ShowDirector;
use App\Models\InstalmentDirector;
use App\Models\Cast;
use App\Models\ShowCast;
use App\Models\InstalmentCast;
use App\Models\Tag;
use App\Models\ShowTag;
use App\Models\Schedule;
use \Carbon\Carbon;

class SerieController extends Controller
{
    public function getAllSeries(Request $req){
        $showModel = (new Show)->newQuery();
        $perpage = $req->perpage;
        
        if($req->has('region')){
            $regionID = Region::where('region_name', $req->region)->value('id');
            
            $showModel->where('region_id', $regionID);
        }

        if($req->has('genres')){
            $genreArr = json_decode($req->genres);
            $genreIDArr = Genre::whereIn('genre_name', $genreArr)->pluck('id');

            $showIDs = ShowGenre::whereIn('genre_id', $genreIDArr)->distinct()->pluck('show_id');

            $showModel->whereIn('id', $showIDs);
        }

        if($req->has('languages')){
            $languageArr = json_decode($req->languages);
            $languageIDArr = Language::whereIn('language_name', $languageArr)->pluck('id');

            $showIDs = ShowLanguage::whereIn('language_id', $languageIDArr)->distinct()->pluck('show_id');

            $showModel->whereIn('id', $showIDs);
        }

        if($perpage == null){
            $seriesArr = $showModel->select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new', 'sub_title')
            ->where('show_type_id', 2)
            ->where('is_publish', true)
            ->get();
        }else{
            $seriesArr = $showModel->select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new', 'sub_title')
            ->where('show_type_id', 2)
            ->where('is_publish', true)
            ->paginate($perpage);
        }
        
        if(count($seriesArr) >= 1){

            $data = [];

            foreach($seriesArr as $indexPos => $series){
                $data[$indexPos]['id'] = $series->id;
                $data[$indexPos]['title'] = $series->title;
                $data[$indexPos]['foreign_title'] = $series->foreign_title;
                $data[$indexPos]['sub_title'] = $series->sub_title;
                $data[$indexPos]['slug'] = '/series/'.$series->slug;
                $data[$indexPos]['show_type'] = ShowType::where('id', $series->show_type_id)->value('show_type');
                $data[$indexPos]['trailer_link_url'] = $series->trailer_link_url;
                $data[$indexPos]['is_new'] = $series->is_new;

                $genreIDs = ShowGenre::where('show_id', $series->id)->pluck('genre_id');
                if(count($genreIDs) >= 1){
                    $genres = Genre::whereIn('id', $genreIDs)->pluck('genre_display_name');
                    $data[$indexPos]['genres'] = $genres;
                }

                if($series->cover_photo_path){
                    if(Storage::disk('public')->exists($series->cover_photo_path)){
                        $data[$indexPos]['cover_photo_path'] = Storage::url($series->cover_photo_path);
                    }
                    else {
                        $data[$indexPos]['cover_photo_path'] = null;
                    }
                }
            }
            return response()->json($data, 200);
        }
        else {
            return response()->json('No series were found that matches your search', 202);
        }
    }

    public function getSeries(Request $req){
        $series = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'short_desc', 'full_desc', 'trivia_desc', 'director_statement', 'banner_path', 'director_photo_path', 'is_new', 'on_demand', 'trailer_link_url', 'sub_title', 'meta_title', 'meta_description', 'cover_photo_alt', 'director_statement_alt', 'tonight_banner_alt')
        ->where('show_type_id', 2)
        ->where('slug', $req->slug)
        ->where('is_publish', true)
        ->first();

        if($series){
            $data = [];

            $data['id'] = $series->id;
            $data['title'] = $series->title;
            $data['foreign_title'] = $series->foreign_title;
            $data['sub_title'] = $series->sub_title;
            // $data['slug'] = '/series/'.$series->slug;
            $data['show_type'] = ShowType::where('id', $series->show_type_id)->value('show_type');
            $data['trailer_link_url'] = $series->trailer_link_url;
            $data['short_desc'] = $series->short_desc;
            $data['full_desc'] = $series->full_desc;
            $data['trivia_desc'] = $series->trivia_desc;
            $data['director_statement'] = $series->director_statement;
            $data['is_new'] = $series->is_new;
            $data['meta_title'] = $series->meta_title; 
            $data['meta_description'] = $series->meta_description;
            $data['on_demand'] = $series->on_demand;
            $data['cover_photo_alt'] = $series->cover_photo_alt;
            $data['director_statement_alt'] = $series->director_statement_alt;
            $data['tonight_banner_alt'] = $series->tonight_banner_alt;
            $genreIDs = ShowGenre::where('show_id', $series->id)->pluck('genre_id');
            if(count($genreIDs) >= 1){
                $genres = Genre::select('genre_name', 'genre_display_name')->whereIn('id', $genreIDs)->get();
                $data['genres'] = $genres;
            }

            $countryIDs = ShowCountry::where('show_id', $series->id)->pluck('country_id');
            if(count($countryIDs) >= 1){
                $countries = Country::whereIn('id', $countryIDs)->pluck('country_display_name');
                $data['countries'] = $countries;
            }

            $languageIDs = Showlanguage::where('show_id', $series->id)->pluck('language_id');
            if(count($languageIDs) >= 1){
                $languages = Language::whereIn('id', $languageIDs)->pluck('language_display_name');
                $data['languages'] = $languages;
            }

            $accoladeIDs = ShowAccolade::where('show_id', $series->id)->pluck('accolade_id');
            if(count($accoladeIDs) >= 1){
                $accolades = Accolade::select('name', 'category', 'cover_photo_path')->whereIn('id', $accoladeIDs)->get();
                foreach($accolades as $accolade){
                    if($accolade->cover_photo_path){
                        if(Storage::disk('public')->exists($accolade->cover_photo_path)){
                            $accolade->cover_photo_path = Storage::url($accolade->cover_photo_path);
                        }
                        else {
                            $accolade->cover_photo_path = null;
                        }
                    }
                }
                $data['accolades'] = $accolades;
            }

            $critics = Critic::select('name', 'critique')->where('show_id', $series->id)->get();
            if(count($critics) >= 1){
                $data['critics'] = $critics;
            }

            if($series->banner_path){
                if(Storage::disk('public')->exists($series->banner_path)){
                    $data['banner_path'] = Storage::url($series->banner_path);
                }
                else {
                    $data['banner_path'] = null;
                }
            }

            if($series->director_photo_path){
                if(Storage::disk('public')->exists($series->director_photo_path)){
                    $data['director_photo_path'] = Storage::url($series->director_photo_path);
                }
                else {
                    $data['director_photo_path'] = null;
                }
            }

            if($series->media){
                $photoGalleryFilePath = null;

                foreach($series->getMedia('series-gallery-collection') as $photoGalleryObj){
                    $photoGalleryFileName = $photoGalleryObj->file_name;
                    $photoGalleryFilePath = 'img/'.$photoGalleryObj->collection_name.'/'.$photoGalleryObj->id.'/'.$photoGalleryFileName;

                    if(Storage::disk('public')->exists($photoGalleryFilePath)){
                        $photoGalleryFilePath = Storage::url($photoGalleryFilePath);
                        $data['photo_gallery_paths'][] = $photoGalleryFilePath;
                    }
                }
            }

            if($genreIDs){
                $showsWithSameGenres = ShowGenre::whereIn('genre_id', $genreIDs)->pluck('show_id');
                if(count($showsWithSameGenres) >= 1){
                    $youMayAlsoLikes = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'cover_photo_path', 'is_new', 'is_publish', 'sub_title')
                    ->where('show_type_id', 2)
                    ->whereNot('id', $series->id)
                    ->where('is_publish', true)
                    ->where('sub_title', '!=', null)
                    ->whereIn('id', $showsWithSameGenres)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();

                    if(count($youMayAlsoLikes) >= 1){
                        foreach($youMayAlsoLikes as $indexPos => $youMayAlsoLike){
                            $data['you_may_also_likes'][$indexPos]['series_id'] = $youMayAlsoLike->id;
                            $data['you_may_also_likes'][$indexPos]['title'] = $youMayAlsoLike->title;
                            $data['you_may_also_likes'][$indexPos]['foreign_title'] = $youMayAlsoLike->foreign_title;
                            $data['you_may_also_likes'][$indexPos]['slug'] = '/series/'.$youMayAlsoLike->slug;
                            $data['you_may_also_likes'][$indexPos]['is_new'] = $youMayAlsoLike->is_new;
                            
                            if($youMayAlsoLike->cover_photo_path){
                                if(Storage::disk('public')->exists($youMayAlsoLike->cover_photo_path)){
                                    $youMayAlsoLike->cover_photo_path = Storage::url($youMayAlsoLike->cover_photo_path);
                                }
                                else {
                                    $youMayAlsoLike->cover_photo_path = null;
                                }
                                $data['you_may_also_likes'][$indexPos]['cover_photo_path'] = $youMayAlsoLike->cover_photo_path;
                            }
                        }
                    }
                }   
            }

            $instalments = Instalment::where('series_id', $series->id)
            ->where('is_publish', true)
            ->orderBy('instalment_number', 'ASC')
            ->get();
    
            if(count($instalments) >= 1){
                foreach($instalments as $instalment){

                    $instalment->slug = '/'.$series->slug.'/'.$instalment->slug;

                    $instalment->instalment_expiry_date = ($instalment->expiry_date == null)? null : $instalment->expiry_date->format('Y-m-d');
                    unset($instalment->expiry_date);

                    if($instalment->cover_photo_path){
                        if(Storage::disk('public')->exists($instalment->cover_photo_path)){
                            $instalment->cover_photo_path = Storage::url($instalment->cover_photo_path);
                        }
                        else {
                            $instalment->cover_photo_path = null;
                        }
                    }

                    $instalmentLanguageIDs = InstalmentLanguage::where('instalment_id', $instalment->id)->pluck('language_id');
                    if(count($instalmentLanguageIDs) >= 1){
                        $instalmentLanguages = Language::whereIn('id', $instalmentLanguageIDs)->pluck('language_display_name');
                        $instalment->instalment_languages = $instalmentLanguages;
                    }

                    $currentDateStr = date('Y-m-d');
                    $currentTimeStr = date('H:i:s');

                    $nowPlayingInstalment = Schedule::select('id', 'show_id', 'instalment_id')
                    ->whereDate('schedule_start_date', $currentDateStr)
                    ->whereTime('schedule_start_time', '<=', $currentTimeStr)
                    ->whereDate('schedule_end_date', $currentDateStr)
                    ->whereTime('schedule_end_time', '>=', $currentTimeStr)
                    ->where('instalment_id', $instalment->id)
                    ->first();

                    $scheduleModel = (new Schedule)->newQuery();

                    // if($nowPlayingInstalment){
                    //     $scheduleModel->where('id', '!=', $nowPlayingInstalment->id)->where('instalment_id', $nowPlayingInstalment->instalment_id);
                    // }
                    // else {
                    //     $scheduleModel->where('instalment_id', $instalment->id);
                    // }

                    // $nextSchedule = $scheduleModel->select('schedule_start_date', 'schedule_start_time')
                    // ->whereDate('schedule_start_date', '>=', $currentDateStr)
                    // ->whereTime('schedule_start_time', '>', $currentTimeStr)
                    // ->whereDate('schedule_end_date', '>=', $currentDateStr)
                    // ->whereTime('schedule_end_time', '>', $currentTimeStr)
                    // ->first();

                    $nextSchedule = $scheduleModel->select('schedule_start_date', 'schedule_start_time')
                    ->where('instalment_id', $instalment->id)
                    ->where(function ($query) {
                    $query->whereDate('schedule_start_date', date('Y-m-d'))
                      ->whereDate('schedule_end_date', date('Y-m-d'))
                      ->whereTime('schedule_start_time', '>', date('H:i:s'))
                      ->whereTime('schedule_end_time', '>', date('H:i:s'))
                      ->orWhere('schedule_start_date', '>', date('Y-m-d'))
                      ->whereDate('schedule_end_date', '>', date('Y-m-d'));
                    })
                    ->orderBy('schedule_start_date')
                    ->orderBy('schedule_start_time')
                    ->first();

                    if($nextSchedule){
                        $nextScheduleDate = date('l, d M', strtotime($nextSchedule->schedule_start_date));
                        $nextScheduleTime = date('g:i A', strtotime($nextSchedule->schedule_start_time));

                        $instalment->next_play = $nextScheduleDate.' at '.$nextScheduleTime;
                    }
                    
                    $data['instalments'][] = $instalment;
                }
            }
            // else {
            //     return response()->json('No instalments are available at the moment', 202);
            // }

            return response()->json($data, 200);
        }
        else {
            return response()->json('This series was not found', 202);
        }
    }

    public function getSeriesInstalment(Request $req){
        $series = Show::select('id', 'title', 'foreign_title', 'show_type_id', 'is_new', 'on_demand', 'short_desc', 'full_desc', 'sub_title', 'meta_title', 'meta_description')
        ->where('show_type_id', 2)
        ->where('slug', $req->seriesSlug)
        ->where('is_publish', true)
        ->first();

        if($series){
            $data = [];

            $series->show_type = ShowType::where('id', $series->show_type_id)->value('show_type');

            unset($series->show_type_id);

            $genreIDs = ShowGenre::where('show_id', $series->id)->pluck('genre_id');
            if(count($genreIDs) >= 1){
                $genres = Genre::select('genre_name', 'genre_display_name')->whereIn('id', $genreIDs)->get();
                $series->genres = $genres;
            }

            $countryIDs = ShowCountry::where('show_id', $series->id)->pluck('country_id');
            if(count($countryIDs) >= 1){
                $countries = Country::whereIn('id', $countryIDs)->pluck('country_display_name');
                $series->countries = $countries;
            }

            $languageIDs = Showlanguage::where('show_id', $series->id)->pluck('language_id');
            if(count($languageIDs) >= 1){
                $languages = Language::whereIn('id', $languageIDs)->pluck('language_display_name');
                $series->languages = $languages;
            }

            $directorIDs = ShowDirector::where('show_id', $series->id)->pluck('director_id');
            if(count($directorIDs) >= 1){
                $directorArr = [];

                $directors = Director::select('id', 'name', 'slug')->whereIn('id', $directorIDs)->get();
                foreach($directors as $director){
                    $director->slug = '/directors/'.$director->slug;
                    $directorArr[] = $director;
                }
                $series->directors = $directorArr;
            }

            $castIDs = ShowCast::where('show_id', $series->id)->pluck('cast_id');
            if(count($castIDs) >= 1){
                $castArr = Cast::select('id', 'name', 'slug', 'profile_photo_path')->whereIn('id', $castIDs)->get();

                foreach($castArr as $cast){
                    $cast->slug = '/casts/'.$cast->slug;
                    if($cast->profile_photo_path){
                        if(Storage::disk('public')->exists($cast->profile_photo_path)){
                            $cast->profile_photo_path = Storage::url($cast->profile_photo_path);
                        }
                        else {
                            $cast->profile_photo_path = null;
                        }
                    }
                }

                $series->cast = $castArr;
            }

            $data['series'] = $series;

            if($genreIDs){
                $showsWithSameGenres = ShowGenre::whereIn('genre_id', $genreIDs)->pluck('show_id');
                if(count($showsWithSameGenres) >= 1){
                    $youMayAlsoLikes = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'cover_photo_path', 'is_new', 'is_publish', 'sub_title', 'meta_title', 'meta_description')
                    ->where('show_type_id', 2)
                    ->whereNot('id', $series->id)
                    ->where('is_publish', true)
                    ->where('sub_title', '!=', null)
                    ->whereIn('id', $showsWithSameGenres)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();

                    if(count($youMayAlsoLikes) >= 1){
                        foreach($youMayAlsoLikes as $indexPos => $youMayAlsoLike){
                            $data['you_may_also_likes'][$indexPos]['series_id'] = $youMayAlsoLike->id;
                            $data['you_may_also_likes'][$indexPos]['title'] = $youMayAlsoLike->title;
                            $data['you_may_also_likes'][$indexPos]['foreign_title'] = $youMayAlsoLike->foreign_title;
                            $data['you_may_also_likes'][$indexPos]['slug'] = '/series/'.$youMayAlsoLike->slug;
                            $data['you_may_also_likes'][$indexPos]['is_new'] = $youMayAlsoLike->is_new;
                            
                            if($youMayAlsoLike->cover_photo_path){
                                if(Storage::disk('public')->exists($youMayAlsoLike->cover_photo_path)){
                                    $youMayAlsoLike->cover_photo_path = Storage::url($youMayAlsoLike->cover_photo_path);
                                }
                                else {
                                    $youMayAlsoLike->cover_photo_path = null;
                                }
                                $data['you_may_also_likes'][$indexPos]['cover_photo_path'] = $youMayAlsoLike->cover_photo_path;
                            }
                        }
                    }
                }   
            }

            $instalment = Instalment::where('series_id', $series->id)
            ->where('slug', $req->slug)
            ->where('is_publish', true)
            ->orderBy('instalment_number', 'ASC')
            ->first();

            if($instalment){

                $data['instalment']['id'] = $instalment->id;
                $data['instalment']['title'] = $instalment->title;
                $data['instalment']['foreign_title'] = $instalment->foreign_title;
                $data['instalment']['sub_title'] = $instalment->sub_title;
                $data['instalment']['expiry_date'] = ($instalment->expiry_date == null)? null: $instalment->expiry_date->format('Y-m-d');
                // $data['instalment']['slug'] = $instalment->slug;
                $data['instalment']['instalment_number'] = $instalment->instalment_number;
                $data['instalment']['release_year'] = $instalment->release_year;
                $data['instalment']['runtime'] = $instalment->runtime;
                $data['instalment']['short_desc'] = $instalment->short_desc;
                $data['instalment']['full_desc'] = $instalment->full_desc;
                $data['instalment']['trivia_desc'] = $instalment->trivia_desc;
                $data['instalment']['trailer_link_url'] = $instalment->trailer_link_url;
                $data['instalment']['is_new'] = $instalment->is_new;
                $data['instalment']['on_demand'] = $instalment->on_demand;
                $data['instalment']['meta_title'] = $instalment->meta_title;
                $data['instalment']['meta_description'] = $instalment->meta_description;
                $data['instalment']['cover_photo_alt'] = $instalment->cover_photo_alt;
                $data['instalment']['banner_alt'] = $instalment->banner_alt;
                $data['instalment']['tonight_banner_alt'] = $instalment->tonight_banner_alt;
                $instalmentDirectorIDs = InstalmentDirector::where('instalment_id', $instalment->id)->pluck('director_id');
                if(count($instalmentDirectorIDs) >= 1){
                    $instalmentDirectorArr = [];

                    $instalmentDirectors = Director::select('id', 'name', 'slug', 'profile_photo_path')->whereIn('id', $instalmentDirectorIDs)->get();
                    foreach($instalmentDirectors as $director){
                        $director->slug = '/directors/'.$director->slug;
                        if($director->profile_photo_path){
                          if(Storage::disk('public')->exists($director->profile_photo_path)){
                              $director->profile_photo_path = Storage::url($director->profile_photo_path);
                          }
                          else {
                              $director->profile_photo_path = null;
                          }
                        }
                        $instalmentDirectorArr[] = $director;
                    }
                    //$instalment->directors = $instalmentDirectorArr;
                    $data['instalment']['directors'] = $instalmentDirectorArr;
                }

                $instalmentCastIDs = InstalmentCast::where('instalment_id', $instalment->id)->pluck('cast_id');
                if(count($instalmentCastIDs) >= 1){
                    $instalmentCastArr = Cast::select('id', 'name', 'slug', 'profile_photo_path')->whereIn('id', $instalmentCastIDs)->get();

                 foreach($instalmentCastArr as $cast){
                        $cast->slug = '/casts/'.$cast->slug;
                        if($cast->profile_photo_path){
                            if(Storage::disk('public')->exists($cast->profile_photo_path)){
                                $cast->profile_photo_path = Storage::url($cast->profile_photo_path);
                            }
                            else {
                                $cast->profile_photo_path = null;
                            }
                        }
                    }

                    //$instalment->cast = $instalmentCastArr;
                    $data['instalment']['casts'] = $instalmentCastArr;
                }

                $instalmentLanguageIDs = InstalmentLanguage::where('instalment_id', $instalment->id)->pluck('language_id');
                if(count($instalmentLanguageIDs) >= 1){
                    $instalmentLanguages = Language::whereIn('id', $instalmentLanguageIDs)->pluck('language_display_name');
                    //$instalment->languages = $instalmentLanguages;
                    $data['instalment']['instalment_languages'][] = $instalmentLanguages;
                }
    
                if($instalment->banner_path){
                    if(Storage::disk('public')->exists($instalment->banner_path)){
                        $data['instalment']['banner_path'] = Storage::url($instalment->banner_path);
                    }
                    else {
                        $data['instalment']['banner_path'] = null;
                    }
                }

                if($instalment->cover_photo_path){
                    if(Storage::disk('public')->exists($instalment->cover_photo_path)){
                        $data['instalment']['cover_photo_path'] = Storage::url($instalment->cover_photo_path);
                    }
                    else {
                        $data['instalment']['cover_photo_path'] = null;
                    }
                }
    
                if($instalment->media){
                    $photoGalleryFilePath = null;
    
                    foreach($instalment->getMedia('instalment-gallery-collection') as $photoGalleryObj){
                        $photoGalleryFileName = $photoGalleryObj->file_name;
                        $photoGalleryFilePath = 'img/'.$photoGalleryObj->collection_name.'/'.$photoGalleryObj->id.'/'.$photoGalleryFileName;
    
                        if(Storage::disk('public')->exists($photoGalleryFilePath)){
                            $photoGalleryFilePath = Storage::url($photoGalleryFilePath);
                            $data['instalment']['photo_gallery_paths'][] = $photoGalleryFilePath;
                        }
                    }
                }

                // continue here
                
                $currentDateStr = date('Y-m-d');
                $currentTimeStr = date('H:i:s');

                $nowPlayingInstalment = Schedule::select('id', 'show_id', 'instalment_id')
                ->whereDate('schedule_start_date', $currentDateStr)
                ->whereTime('schedule_start_time', '<=', $currentTimeStr)
                ->whereDate('schedule_end_date', $currentDateStr)
                ->whereTime('schedule_end_time', '>', $currentTimeStr)
                ->where('show_id', $series->id)
                ->where('instalment_id', $instalment->id)
                ->first();

                $scheduleModel = (new Schedule)->newQuery();

                // if($nowPlayingInstalment){
                //     $scheduleModel->where('id', '!=', $nowPlayingInstalment->id)->where('show_id', $nowPlayingInstalment->show_id)->where('instalment_id', $nowPlayingInstalment->instalment_id);
                // }
                // else {
                //     $scheduleModel->where('show_id', $series->id)->where('instalment_id', $instalment->id);
                // }

                // $nextSchedule = $scheduleModel->select('schedule_start_date', 'schedule_start_time')
                // ->whereDate('schedule_start_date', '>=', $currentDateStr)
                // ->whereTime('schedule_start_time', '>', $currentTimeStr)
                // ->whereDate('schedule_end_date', '>=', $currentDateStr)
                // ->whereTime('schedule_end_time', '>', $currentTimeStr)
                // ->first();

                $nextSchedule = $scheduleModel->select('schedule_start_date', 'schedule_start_time')
                    ->where('instalment_id', $instalment->id)
                    ->where(function ($query) {
                    $query->whereDate('schedule_start_date', date('Y-m-d'))
                      ->whereDate('schedule_end_date', date('Y-m-d'))
                      ->whereTime('schedule_start_time', '>', date('H:i:s'))
                      ->whereTime('schedule_end_time', '>', date('H:i:s'))
                      ->orWhere('schedule_start_date', '>', date('Y-m-d'))
                      ->whereDate('schedule_end_date', '>', date('Y-m-d'));
                    })
                    ->orderBy('schedule_start_date')
                    ->orderBy('schedule_start_time')
                    ->first();

                if($nextSchedule){
                    $nextScheduleDate = date('l, d M', strtotime($nextSchedule->schedule_start_date));
                    $nextScheduleTime = date('g:i A', strtotime($nextSchedule->schedule_start_time));

                    $data['instalment']['next_play'] = $nextScheduleDate.' at '.$nextScheduleTime;
                }
               
                return response()->json($data, 200);
            }
            else {
                return response()->json('The instalment that was associated with this series was not found', 202);
            }
        }
    }
}
