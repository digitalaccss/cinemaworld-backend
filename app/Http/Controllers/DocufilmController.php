<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Show;
use App\Models\ShowType;
use App\Models\Genre;
use App\Models\ShowGenre;
use App\Models\Region;
use App\Models\Country;
use App\Models\ShowCountry;
use App\Models\Language;
use App\Models\ShowLanguage;
use App\Models\Accolade;
use App\Models\ShowAccolade;
use App\Models\Critic;
use App\Models\Director;
use App\Models\ShowDirector;
use App\Models\Cast;
use App\Models\ShowCast;
use App\Models\ShowTag;
use App\Models\Tag;
use App\Models\Schedule;
use \Carbon\Carbon;

class DocufilmController extends Controller
{
    public function getAllDocufilms(Request $req){
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

        if($req->has('tags')){
            $tagArr = json_decode($req->tags);
            $tagIDArr = Tag::whereIn('tag_name', $tagArr)->pluck('id');
            
            $showIDs = ShowTag::whereIn('tag_id', $tagIDArr)->distinct()->pluck('show_id');

            $showModel->whereIn('id', $showIDs);
        }

        if($perpage == null){
            $docuFilms = $showModel->select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new', 'sub_title', 'expiry_date')
            ->where('show_type_id', 3)
            ->where('is_publish', true)
            ->get();
        }else{
            $docuFilms = $showModel->select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new', 'sub_title', 'expiry_date')
            ->where('show_type_id', 3)
            ->where('is_publish', true)
            ->paginate($perpage);
        }
        
        if(count($docuFilms) >= 1){

            $data = [];
            
            foreach($docuFilms as $indexPos => $docuFilm){
                
                $data[$indexPos]['id'] = $docuFilm->id;
                $data[$indexPos]['title'] = $docuFilm->title;
                $data[$indexPos]['slug'] = '/docufilms/'.$docuFilm->slug;
                $data[$indexPos]['show_type'] = ShowType::where('id', $docuFilm->show_type_id)->value('show_type');
                $data[$indexPos]['trailer_link_url'] = $docuFilm->trailer_link_url;
                $data[$indexPos]['foreign_title'] = $docuFilm->foreign_title;
                $data[$indexPos]['sub_title'] = $docuFilm->sub_title;
                $data[$indexPos]['expiry_date'] = ($docuFilm->expiry_date == null)? null : $docuFilm->expiry_date->format('Y-m-d');
                $data[$indexPos]['is_new'] = $docuFilm->is_new;
                
                $genreIDs = ShowGenre::where('show_id', $docuFilm->id)->pluck('genre_id');
                if(count($genreIDs) >= 1){
                    $genres = Genre::whereIn('id', $genreIDs)->pluck('genre_display_name');
                    $data[$indexPos]['genres'] = $genres;
                }
                
                if($docuFilm->cover_photo_path){
                    if(Storage::disk('public')->exists($docuFilm->cover_photo_path)){
                        $data[$indexPos]['cover_photo_path'] = Storage::url($docuFilm->cover_photo_path);
                    }
                    else {
                        $data[$indexPos]['cover_photo_path'] = null;
                    }
                }
            }
            return response()->json($data, 200);
        }
        else {
            return response()->json('No docu films were found that matches your search', 202);
        }
    }

    public function getDocuFilm(Request $req){
        $docuFilm = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'release_year', 'runtime', 'short_desc', 'full_desc', 'trivia_desc', 'trailer_link_url', 'director_statement', 'cover_photo_path', 'banner_path', 'director_photo_path', 'is_new', 'on_demand', 'sub_title', 'expiry_date', 'meta_title', 'meta_description')
        ->where('show_type_id', 3)
        ->where('slug', $req->slug)
        ->where('is_publish', true)
        ->first();

        if($docuFilm){
            $data = [];

            $data['id'] = $docuFilm->id;
            $data['title'] = $docuFilm->title;
            $data['foreign_title'] = $docuFilm->foreign_title;
            $data['sub_title'] = $docuFilm->sub_title;
            $data['expiry_date'] = ($docuFilm->expiry_date == null)? null : $docuFilm->expiry_date->format('Y-m-d');
            // $data['slug'] = '/films/'.$film->slug;
            $data['show_type'] = ShowType::where('id', $docuFilm->show_type_id)->value('show_type');
            $data['release_year'] = $docuFilm->release_year;
            $data['runtime'] = $docuFilm->runtime;
            $data['short_desc'] = $docuFilm->short_desc;
            $data['full_desc'] = $docuFilm->full_desc;
            $data['trivia_desc'] = $docuFilm->trivia_desc;
            $data['trailer_link_url'] = $docuFilm->trailer_link_url;
            $data['director_statement'] = $docuFilm->director_statement;
            $data['is_new'] = $docuFilm->is_new;
            $data['on_demand'] = $docuFilm->on_demand;
            $data['meta_title'] = $docuFilm->meta_title;
            $data['meta_description'] = $docuFilm->meta_description;
            
            $genreIDs = ShowGenre::where('show_id', $docuFilm->id)->pluck('genre_id');
            if(count($genreIDs) >= 1){
                $genres = Genre::select('genre_name', 'genre_display_name')->whereIn('id', $genreIDs)->get();
                $data['genres'] = $genres;
            }

            $countryIDs = ShowCountry::where('show_id', $docuFilm->id)->pluck('country_id');
            if(count($countryIDs) >= 1){
                $countries = Country::whereIn('id', $countryIDs)->pluck('country_display_name');
                $data['countries'] = $countries;
            }

            $languageIDs = Showlanguage::where('show_id', $docuFilm->id)->pluck('language_id');
            if(count($languageIDs) >= 1){
                $languages = Language::whereIn('id', $languageIDs)->pluck('language_display_name');
                $data['languages'] = $languages;
            }

            $accoladeIDs = ShowAccolade::where('show_id', $docuFilm->id)->pluck('accolade_id');
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

            $critics = Critic::select('name', 'critique')->where('show_id', $docuFilm->id)->get();
            if(count($critics) >= 1){
                $data['critics'] = $critics;
            }

            $directorIDs = ShowDirector::where('show_id', $docuFilm->id)->pluck('director_id');
            if(count($directorIDs) >= 1){
                $directors = Director::select('id', 'name', 'slug', 'profile_photo_path')->whereIn('id', $directorIDs)->get();
                foreach($directors as $director){
                    $director->slug = '/directors/'.$director->slug;
                    if($director->profile_photo_path){
                      if(Storage::disk('public')->exists($director->profile_photo_path)){
                          $director->profile_photo_path = Storage::url($director->profile_photo_path);
                      }
                      else {
                          $director->profile_photo_path = null;
                      }
                  }
                    $data['directors'][] = $director;
                }
            }

            $castIDs = ShowCast::where('show_id', $docuFilm->id)->pluck('cast_id');
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
                    $data['casts'][] = $cast;
                }
            }
            
            if($docuFilm->banner_path){
                if(Storage::disk('public')->exists($docuFilm->banner_path)){
                    $data['banner_path'] = Storage::url($docuFilm->banner_path);
                }
                else {
                    $data['banner_path'] = null;
                }
            }

            if($docuFilm->media){
                $photoGalleryFilePath = null;

                foreach($docuFilm->getMedia('docufilm-gallery-collection') as $photoGalleryObj){
                    $photoGalleryFileName = $photoGalleryObj->file_name;
                    $photoGalleryFilePath = 'img/'.$photoGalleryObj->collection_name.'/'.$photoGalleryObj->id.'/'.$photoGalleryFileName;

                    if(Storage::disk('public')->exists($photoGalleryFilePath)){
                        $photoGalleryFilePath = Storage::url($photoGalleryFilePath);
                        $data['photo_gallery_paths'][] = $photoGalleryFilePath;
                    }
                }
            }

            if($docuFilm->director_photo_path){
                if(Storage::disk('public')->exists($docuFilm->director_photo_path)){
                    $data['director_photo_path'] = Storage::url($docuFilm->director_photo_path);
                }
                else {
                    $data['director_photo_path'] = null;
                }
            }

            if($genreIDs){
                $showsWithSameGenres = ShowGenre::whereIn('genre_id', $genreIDs)->pluck('show_id');
                if(count($showsWithSameGenres) >= 1){
                    $youMayAlsoLikes = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'cover_photo_path', 'is_new', 'is_publish')
                    ->where('show_type_id', 3)
                    ->whereNot('id', $docuFilm->id)
                    ->where('is_publish', true)
                    ->where('expiry_date', '>=', Carbon::today('Asia/Singapore'))
                    ->whereIn('id', $showsWithSameGenres)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();

                    if(count($youMayAlsoLikes) >= 1){
                        foreach($youMayAlsoLikes as $indexPos => $youMayAlsoLike){
                            $data['you_may_also_likes'][$indexPos]['docufilm_id'] = $youMayAlsoLike->id;
                            $data['you_may_also_likes'][$indexPos]['title'] = $youMayAlsoLike->title;
                            $data['you_may_also_likes'][$indexPos]['foreign_title'] = $youMayAlsoLike->foreign_title;
                            $data['you_may_also_likes'][$indexPos]['slug'] = '/docufilms/'.$youMayAlsoLike->slug;
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

            $currentDateStr = date('Y-m-d');
            $currentTimeStr = date('H:i:s');

            $nowPlayingFilm = Schedule::select('id', 'show_id')
            ->whereDate('schedule_start_date', $currentDateStr)
            ->whereTime('schedule_start_time', '<=', $currentTimeStr)
            ->whereDate('schedule_end_date', $currentDateStr)
            ->whereTime('schedule_end_time', '>=', $currentTimeStr)
            ->where('show_id', $docuFilm->id)
            ->first();

            $scheduleModel = (new Schedule)->newQuery();

            // $nextSchedule = $scheduleModel->select('schedule_start_date', 'schedule_start_time')
            // ->whereDate('schedule_start_date', '>=', $currentDateStr)
            // ->whereTime('schedule_start_time', '>', $currentTimeStr)
            // ->whereDate('schedule_end_date', '>=', $currentDateStr)
            // ->whereTime('schedule_end_time', '>', $currentTimeStr)
            // ->first();

            $nextSchedule = $scheduleModel->select('schedule_start_date', 'schedule_start_time')
            ->where('show_id', $docuFilm->id)
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
                $data['next_play'] = $nextScheduleDate.' at '.$nextScheduleTime;
            }

            // $nextScheduleDate = date('l, d M', strtotime($nextSchedule->schedule_start_date));
            // $nextScheduleTime = date('g:i A', strtotime($nextSchedule->schedule_start_time));
            // $data['next_play'] = $nextScheduleDate.' at '.$nextScheduleTime;
            
            return response()->json($data, 200);
        }
        else {
            return response()->json('This docu film was not found', 202);
        }
    }
}
