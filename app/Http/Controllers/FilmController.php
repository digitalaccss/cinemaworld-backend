<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
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

class FilmController extends Controller
{
    public function getAllFilms(Request $req){
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
            $films = $showModel->select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new', 'sub_title', 'expiry_date', 'created_at')
            ->where('show_type_id', 1)
            ->where('is_publish', true)
            ->get();
        }else{
            $films = $showModel->select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'trailer_link_url', 'cover_photo_path', 'is_new', 'sub_title', 'expiry_date', 'created_at')
            ->where('show_type_id', 1)
            ->where('is_publish', true)
            ->paginate($perpage);
        }
        
        if(count($films) >= 1){

            $data = [];
            
            foreach($films as $indexPos => $film){
                
                $data[$indexPos]['id'] = $film->id;
                $data[$indexPos]['title'] = $film->title;
                $data[$indexPos]['slug'] = '/films/'.$film->slug;
                $data[$indexPos]['show_type'] = ShowType::where('id', $film->show_type_id)->value('show_type');
                $data[$indexPos]['trailer_link_url'] = $film->trailer_link_url;
                $data[$indexPos]['foreign_title'] = $film->foreign_title;
                $data[$indexPos]['sub_title'] = $film->sub_title;
                $data[$indexPos]['expiry_date'] = ($film->expiry_date == null)? null: $film->expiry_date->format('Y-m-d');
                $data[$indexPos]['is_new'] = $film->is_new;
                $data[$indexPos]['tonight_banner_alt'] = $film->tonight_banner_alt;
                $data[$indexPos]['banner_alt'] = $film->banner_alt;
                $data[$indexPos]['image_alt'] = $film->image_alt;
                $data[$indexPos]['created_at'] = $film->created_at;
                $data[$indexPos]['director_statement_alt'] = $film->director_statement_alt;
        
                $genreIDs = ShowGenre::where('show_id', $film->id)->pluck('genre_id');
                if(count($genreIDs) >= 1){
                    $genres = Genre::whereIn('id', $genreIDs)->pluck('genre_display_name');
                    $data[$indexPos]['genres'] = $genres;
                }
                
                if($film->cover_photo_path){
                    if(Storage::disk('public')->exists($film->cover_photo_path)){
                        $data[$indexPos]['cover_photo_path'] = Storage::url($film->cover_photo_path);
                    }
                    else {
                        $data[$indexPos]['cover_photo_path'] = null;
                    }
                }
            }
            return response()->json($data, 200);
        }
        else {
            return response()->json('No films were found that matches your search', 202);
        }
    }

    public function getFilm(Request $req){
        $film = Show::select('id', 'title', 'foreign_title', 'slug',  'show_type_id', 'release_year', 'runtime', 'short_desc', 'full_desc', 'trivia_desc', 'trailer_link_url', 'director_statement', 'cover_photo_path', 'banner_path', 'director_photo_path', 'is_new', 'on_demand', 'sub_title', 'expiry_date',  'meta_title', 'meta_description',  'director_statement_alt', 'tonight_banner_alt', 'banner_alt', 'image_alt')
        ->where('show_type_id', 1)
        ->where('slug', $req->slug)
        ->where('is_publish', true)
        ->first();
        
        if($film){
            $data = [];

            $data['id'] = $film->id;
            $data['title'] = $film->title;
            $data['foreign_title'] = $film->foreign_title;
            $data['sub_title'] = $film->sub_title;
            $data['expiry_date'] = ($film->expiry_date == null)? null : $film->expiry_date->format('Y-m-d');
            // $data['slug'] = '/films/'.$film->slug;
            $data['show_type'] = ShowType::where('id', $film->show_type_id)->value('show_type');
            $data['release_year'] = $film->release_year;
            $data['runtime'] = $film->runtime;
            $data['short_desc'] = $film->short_desc;
            $data['full_desc'] = $film->full_desc;
            $data['trivia_desc'] = $film->trivia_desc;
            $data['trailer_link_url'] = $film->trailer_link_url;
            $data['director_statement'] = $film->director_statement;
            $data['is_new'] = $film->is_new;
            $data['on_demand'] = $film->on_demand;
            $data['meta_title'] = $film->meta_title ?? '';
            $data['meta_description'] = $film->meta_description ?? '';
            $data['banner_alt'] = $film->banner_alt ?? '';
            $data['image_alt'] = $film->image_alt ?? '';            
            $data['director_statement_alt'] = $film->director_statement_alt;
            $data['tonight_banner_alt'] = $film->tonight_banner_alt;
           
             
            $genreIDs = ShowGenre::where('show_id', $film->id)->pluck('genre_id');
            if(count($genreIDs) >= 1){
                $genres = Genre::select('genre_name', 'genre_display_name')->whereIn('id', $genreIDs)->get();
                $data['genres'] = $genres->toArray() ?? [];
            }

            $countryIDs = ShowCountry::where('show_id', $film->id)->pluck('country_id');
            if(count($countryIDs) >= 1){
                $countries = Country::whereIn('id', $countryIDs)->pluck('country_display_name');
                $data['countries'] = $countries->toArray() ?? [];
            }

            $languageIDs = Showlanguage::where('show_id', $film->id)->pluck('language_id');
            if(count($languageIDs) >= 1){
                $languages = Language::whereIn('id', $languageIDs)->pluck('language_display_name');
                $data['languages'] = $languages->toArray() ?? [];
            }
            
            $accoladeIDs = ShowAccolade::where('show_id', $film->id)->pluck('accolade_id');
            if(count($accoladeIDs) >= 1){
                $accolades = Accolade::select('name', 'category', 'cover_photo_path', 'cover_photo_alt')->whereIn('id', $accoladeIDs)->get();
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
                $data['accolades'] = $accolades->toArray() ?? [];
            }

            $critics = Critic::select('name', 'critique')->where('show_id', $film->id)->get();
            if(count($critics) >= 1){
                $data['critics'] = $critics->toArray() ?? [];
            }

            $directorIDs = ShowDirector::where('show_id', $film->id)->pluck('director_id');
            if(count($directorIDs) >= 1){
                $directors = Director::select('id', 'name', 'slug', 'profile_photo_path', 'profile_photo_alt')->whereIn('id', $directorIDs)->get();
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
                    $data['directors'] = $directors->map(function ($director) {
                        return [
                            'id' => $director->id,
                            'name' => $director->name,
                            'slug' => $director->slug,
                            'profile_photo_path' => $director->profile_photo_path,
                            'profile_photo_alt' => $director->profile_photo_alt,
                        ];
                    })->toArray();
                }
            }
           
            $castIDs = ShowCast::where('show_id', $film->id)->pluck('cast_id');
            if(count($castIDs) >= 1){
                $castArr = Cast::select('id', 'name', 'slug', 'profile_photo_path', 'profile_photo_alt')->whereIn('id', $castIDs)->get();

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
                    $data['casts'] = $castArr->map(function ($cast) {
                        return [
                            'id' => $cast->id,
                            'name' => $cast->name,
                            'slug' => $cast->slug,
                            'profile_photo_path' => $cast->profile_photo_path,
                            'profile_photo_alt' => $cast->profile_photo_alt,
                        ];
                    })->toArray();
                }
                
            }
            
            if($film->banner_path){
                if(Storage::disk('public')->exists($film->banner_path)){
                    $data['banner_path'] = Storage::url($film->banner_path);
                }
                else {
                    $data['banner_path'] = null;
                }
            }
            
            if ($film->media && count($film->media) > 0) {
                $data['photo_gallery_paths'] = []; // Initialize
            
                foreach ($film->media as $mediaItem) {
                    $photoGalleryFilePath = 'img/' . $mediaItem['collection_name'] . '/' . $mediaItem['id'] . '/' . $mediaItem['file_name'];
            
                    Log::info('Generated Photo Path:', ['path' => $photoGalleryFilePath]);
            
                    if (Storage::disk('public')->exists($photoGalleryFilePath)) {
                        $url = Storage::url($photoGalleryFilePath);
                        Log::info('Photo Path Exists:', ['url' => $url]);
                        $data['photo_gallery_paths'][] = $url;
                    } else {
                        Log::warning('Photo Path Does Not Exist:', ['path' => $photoGalleryFilePath]);
                        $data['photo_gallery_paths'][] = null;
                    }
                }
            
                Log::info('Final Photo Gallery Paths:', $data['photo_gallery_paths']); // Final log
            }

            if($film->director_photo_path){
                if(Storage::disk('public')->exists($film->director_photo_path)){
                    $data['director_photo_path'] = Storage::url($film->director_photo_path);
                    $data['director_statement_alt'] = $film->director_statement_alt;
                }
                else {
                    $data['director_photo_path'] = null;
                }
            }
           
            if($genreIDs){
                $showsWithSameGenres = ShowGenre::whereIn('genre_id', $genreIDs)->pluck('show_id');
                if(count($showsWithSameGenres) >= 1){
                    $youMayAlsoLikes = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'cover_photo_path', 'cover_photo_alt', 'is_new', 'is_publish')
                    ->where('show_type_id', 1)
                    ->whereNot('id', $film->id)
                    ->where('is_publish', true)
                    ->where('expiry_date', '>=', Carbon::today('Asia/Singapore'))
                    ->whereIn('id', $showsWithSameGenres)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();
               
                    if(count($youMayAlsoLikes) >= 1){
                        foreach($youMayAlsoLikes as $indexPos => $youMayAlsoLike){
                            $data['you_may_also_likes'][$indexPos]['film_id'] = $youMayAlsoLike->id;
                            $data['you_may_also_likes'][$indexPos]['title'] = $youMayAlsoLike->title;
                            $data['you_may_also_likes'][$indexPos]['foreign_title'] = $youMayAlsoLike->foreign_title;
                            $data['you_may_also_likes'][$indexPos]['slug'] = '/films/'.$youMayAlsoLike->slug;
                            $data['you_may_also_likes'][$indexPos]['is_new'] = $youMayAlsoLike->is_new;
                            $data['you_may_also_likes'][$indexPos]['cover_photo_alt'] = $youMayAlsoLike->cover_photo_alt || null;

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
            ->where('show_id', $film->id)
            ->first();

            $scheduleModel = (new Schedule)->newQuery();

            // $nextSchedule = $scheduleModel->select('schedule_start_date', 'schedule_start_time')
            // ->whereDate('schedule_start_date', '>=', $currentDateStr)
            // ->whereTime('schedule_start_time', '>', $currentTimeStr)
            // ->whereDate('schedule_end_date', '>=', $currentDateStr)
            // ->whereTime('schedule_end_time', '>', $currentTimeStr)
            // ->first();

            $nextSchedule = $scheduleModel->select('schedule_start_date', 'schedule_start_time')
            ->where('show_id', $film->id)
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
            return response()->json('This film was not found', 202);
        }
    }
}
