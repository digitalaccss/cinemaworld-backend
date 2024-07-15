<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Show;
use App\Models\Instalment;
use App\Models\ShowType;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function getScheduleShows(Request $req){
        if($req->has('startDate')){
            $schedules = Schedule::select('id', 'schedule_start_date', 'schedule_start_time', 'show_id', 'instalment_id')->where('schedule_start_date', $req->startDate)->orderBy('schedule_start_time')->get();

            if(count($schedules) >= 1){

                $data = [];
    
                foreach($schedules as $indexPos => $schedule){
                    $show = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'short_desc', 'trailer_link_url', 'cover_photo_path', 'is_new', 'banner_path')
                    ->where('id', $schedule->show_id)
                    ->where('is_publish', true)
                    ->first();
                    $instalment = Instalment::select('id', 'title', 'foreign_title', 'slug', 'instalment_number', 'series_id', 'short_desc', 'trailer_link_url', 'cover_photo_path', 'is_new', 'banner_path')
                    ->where('id', $schedule->instalment_id)
                    ->where('is_publish', true)
                    ->first();

                    if($show){
                        
                        $show->show_type = ShowType::where('id', $show->show_type_id)->value('show_type');
    
                        switch($show->show_type_id){
                            case 1:
                                $show->slug = '/films/'.$show->slug;
                                break;
                            case 2:
                                //$show->slug = '/series/'.$show->slug;
                                //$instalments = Instalment::select('id', 'title', 'foreign_title', 'slug', 'instalment_number', 'release_year', 'runtime', 'full_desc', 'trailer_link_url', 'cover_photo_path')->where('series_id', $series->id)->orderBy('instalment_number', 'ASC')->get();
                                //$instalment = Instalment::select('id', 'title', 'foreign_title', 'slug', 'instalment_number', 'show_type_id', 'short_desc', 'full_desc', 'trailer_link_url', 'cover_photo_path')
                                //->where('series_id', $show->id)
                                //->where('id', $schedule->instalment_id)
                                //->get();
                                //->first();
                                if($instalment){
                                    //$instalment->slug = '/series/'.$show->slug.'/instalments/'.$instalment->slug;
                                    $instalment->slug = '/series/'.$show->slug.'/'.$instalment->slug;
                                }
                                $show->slug = '/series/'.$show->slug;
                                break;
                            case 3:
                                $show->slug = '/docufilms/'.$show->slug;
                                break;
                            case 4:
                                $show->slug = '/shorts/'.$show->slug;
                                break;
                        }

                        if($show->cover_photo_path){
                            if(Storage::disk('public')->exists($show->cover_photo_path)){
                                $show->cover_photo_path = Storage::url($show->cover_photo_path);
                            }
                            else {
                                $show->cover_photo_path = null;
                            }
                        }

                        if($show->banner_path){
                            if(Storage::disk('public')->exists($show->banner_path)){
                                $show->banner_path = Storage::url($show->banner_path);
                            }
                            else {
                                $show->banner_path = null;
                            }
                        }

                        unset($show->show_type_id);

                        $show->schedule_start_date = date('Y-m-d', strtotime($schedule->schedule_start_date));
                        $show->schedule_start_time = date('g:i A', strtotime($schedule->schedule_start_time));

                        if($instalment){
                            if($instalment->cover_photo_path){
                                if(Storage::disk('public')->exists($instalment->cover_photo_path)){
                                    $instalment->cover_photo_path = Storage::url($instalment->cover_photo_path);
                                }
                                else {
                                    $instalment->cover_photo_path = null;
                                }
                            }

                            if($instalment->banner_path){
                                if(Storage::disk('public')->exists($instalment->banner_path)){
                                    $instalment->banner_path = Storage::url($instalment->banner_path);
                                }
                                else {
                                    $instalment->banner_path = null;
                                }
                            }
                            $show->instalment_id = $instalment->id;
                            $show->instalment_title = $instalment->title;
                            $show->instalment_foreign_title = $instalment->foreign_title;
                            $show->instalment_slug = $instalment->slug;
                            $show->instalment_cover_photo_path = $instalment->cover_photo_path;
                            $show->instalment_banner_path = $instalment->banner_path;
                            $show->instalment_number = $instalment->instalment_number;
                            $show->instalment_short_desc = $instalment->short_desc;
                            $show->instalment_trailer_link_url = $instalment->trailer_link_url;
                            $show->instalment_is_new = $instalment->is_new;
                        }
                        $data['shows'][] = $show;
                        // if($instalment){
                        //     $data['test'] = "instalment exist";
                        //     $instalment->cover_photo_path = $show->cover_photo_path;
                        //     $instalment->schedule_start_date = date('Y-m-d', strtotime($schedule->schedule_start_date));
                        //     $instalment->schedule_start_time = date('g:i A', strtotime($schedule->schedule_start_time));
                        //     $data['instalments'][] = $instalment;
                        // }else{
                        //     $data['test'] = "instalment not exist";
                        //     $show->schedule_start_date = date('Y-m-d', strtotime($schedule->schedule_start_date));
                        //     $show->schedule_start_time = date('g:i A', strtotime($schedule->schedule_start_time));
                        //     $data['shows'][] = $show;
                        // }
                        
                    }
                }
                return response()->json($data, 200);
            }
            else {
                return response()->json('No shows are currently available on this date at the moment', 202);
            }
        }
        else {
            return response()->json('Please select a date to view more shows', 202);
        }
    }

    public function getNowPlayingShow(Request $req){
        $currentDateStr = date('Y-m-d');
        $currentTimeStr = date('H:i:s');

        // $currentTimeStr = $req->currentTime;
        // $endTimeStr = $req->endTime;

        $nowPlayingShow = Schedule::select('id', 'schedule_start_date', 'schedule_start_time', 'show_id', 'instalment_id')
        ->whereDate('schedule_start_date', $currentDateStr)
        ->whereTime('schedule_start_time', '<=', $currentTimeStr)
        ->whereDate('schedule_end_date', $currentDateStr)
        ->whereTime('schedule_end_time', '>=', $currentTimeStr)
        ->first();

        if($nowPlayingShow){

            $data = [];

            $show = Show::select('id', 'title', 'foreign_title', 'show_type_id', 'full_desc', 'trailer_link_url', 'banner_path', 'is_new', 'slug')
            ->where('id', $nowPlayingShow->show_id)
            ->where('is_publish', true)
            ->first();

            if($show){
                switch($show->show_type_id){
                    case 1:
                        $show->slug = '/films/'.$show->slug;
                        break;
                    case 2:
                        $show->slug = '/series/'.$show->slug;
                        break;
                    case 3:
                        $show->slug = '/docufilms/'.$show->slug;
                        break;
                    case 4:
                        $show->slug = '/shorts/'.$show->slug;
                        break;
                }

                if($nowPlayingShow->instalment_id){
                    $instalment = Instalment::select('id', 'title', 'foreign_title', 'full_desc', 'trailer_link_url', 'banner_path', 'is_new', 'slug')
                    ->where('series_id', $nowPlayingShow->show_id)
                    ->where('id', $nowPlayingShow->instalment_id)
                    ->where('is_publish', true)
                    ->first();

                    if($instalment){
                        $seriesSlug = $show->slug;
                        $show->instalment_id = $instalment->id;
                        $show->title = $show->title.' - '.$instalment->title;
                        $show->foreign_title = $show->foreign_title.' - '.$instalment->foreign_title;
                        $show->full_desc = $instalment->full_desc;
                        $show->trailer_link_url = $instalment->trailer_link_url;
                        $show->banner_path = $instalment->banner_path;
                        $show->is_new = $instalment->is_new;
                        $show->slug = $seriesSlug.'/'.$instalment->slug;
                    }
                }

                $show->show_type = ShowType::where('id', $show->show_type_id)->value('show_type');
    
                if($show->banner_path){
                    if(Storage::disk('public')->exists($show->banner_path)){
                        $show->banner_path = Storage::url($show->banner_path);
                    }
                    else {
                        $show->banner_path = null;
                    }
                }

                $show->schedule_start_date = date('Y-m-d', strtotime($nowPlayingShow->schedule_start_date));
                $show->schedule_start_time = date('g:i A', strtotime($nowPlayingShow->schedule_start_time));

                unset($show->show_type_id);

                $data['now_playing_show'] = $show;

                // $nextSchedules = Schedule::select('schedule_start_date', 'schedule_start_time')
                // ->where('id', '!=', $nowPlayingShow->id)
                // ->where('show_id', $nowPlayingShow->show_id)
                // ->whereDate('schedule_start_date', '>=', $currentDateStr)
                // ->whereDate('schedule_end_date', '>=', $currentDateStr)
                // ->whereTime('schedule_start_time', '>', $currentTimeStr)
                // ->whereTime('schedule_end_time', '>', $currentTimeStr)
                // ->get();

                $nextSchedules = Schedule::select('schedule_start_date', 'schedule_start_time')
                ->whereNot('id', $nowPlayingShow->id)
                ->where('show_id', $nowPlayingShow->show_id)
                ->where('instalment_id', $nowPlayingShow->instalment_id)
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
                ->get();

                // if($nextSchedules){
                //     foreach($nextSchedules as $indexPos => $nextSchedule){
                //         if($nextSchedule->schedule_start_date == $currentDateStr && $nextSchedule->schedule_end_date == $currentDateStr && $nextSchedule->schedule_start_time > $currentTimeStr && $nextSchedule->schedule_end_time > $currentTimeStr){
                //             //$nextSchedules->push($nextSchedule);
                //             $data['next_plays'][$indexPos] = $nextSchedule;
                //         }else if($nextSchedule->schedule_start_date > $currentDateStr && $nextSchedule->schedule_end_date > $currentDateStr){
                //             //$nextSchedules->push($nextSchedule);
                //             $data['next_plays'][$indexPos] = $nextSchedule;
                //         }
                //     }
                // }

                //if(count($nextSchedules) >= 1){
                if($nextSchedules){
                    foreach($nextSchedules as $indexPos => $nextSchedule){
                        $nextSchedule->schedule_start_date = date('d M Y', strtotime($nextSchedule->schedule_start_date));
                        $nextSchedule->schedule_start_time = date('g:i A', strtotime($nextSchedule->schedule_start_time));
                        
                        $data['next_plays'][$indexPos] = $nextSchedule;
                    }
                }

                return response()->json($data, 200);
            }/*else{
                return response()->json('No show is currently playing', 202);
            }*/
        }
        else {
            return response()->json('No show is currently playing', 202);
        }
    }
}
