<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Show;
use App\Models\Instalment;
use App\Models\ShowType;
use App\Models\Director;
use App\Models\ShowDirector;
use App\Models\InstalmentDirector;

class DirectorController extends Controller
{
    public function getDirector(Request $req){
        $director = Director::select('id', 'name', 'slug', 'description', 'profile_photo_path', 'profile_photo_alt')
        ->where('slug', $req->slug)
        ->first();

        if($director){

            $data = [];

            $data['id'] = $director->id;
            $data['name'] = $director->name;
            // $data['slug'] = $director->slug;
            $data['title'] = 'Director';
            $data['description'] = $director->description;
            $data['profile_photo_alt'] = $director->profile_photo_alt;
            if($director->profile_photo_path){
                if(Storage::disk('public')->exists($director->profile_photo_path)){
                    $data['profile_photo_path'] = Storage::url($director->profile_photo_path);
                }
                else {
                    $data['profile_photo_path'] = null;
                }
            }

            $showIDs = ShowDirector::where('director_id', $director->id)->pluck('show_id');
            if(count($showIDs) >= 1){
                $showsArr = Show::select('id', 'title', 'foreign_title', 'slug', 'show_type_id', 'cover_photo_path', 'is_new')
                ->whereIn('id', $showIDs)
                ->where('is_publish', true)
                ->get();
                
                if(count($showsArr) >= 1){
                    foreach($showsArr as $show){
                        $show->show_type = ShowType::where('id', $show->show_type_id)->value('show_type');

                        switch($show->show_type_id){
                            case 1:
                                $show->slug = '/films/'.$show->slug;
                                $data['shows']['films'][] = $show;
                                break;
                            case 2:
                                $show->slug = '/series/'.$show->slug;
                                $data['shows']['series'][] = $show;
                                break;
                            case 3:
                                $show->slug = '/docufilms/'.$show->slug;
                                $data['shows']['docufilms'][] = $show;
                                break;
                            case 4:
                                $show->slug = '/shorts/'.$show->slug;
                                $data['shows']['shorts'][] = $show;
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
                        
                        unset($show->show_type_id);
                    }
                }
            }

            $instalmentIDs = InstalmentDirector::where('director_id', $director->id)->pluck('instalment_id');
            if(count($instalmentIDs) >= 1){
                $instalmentsArr = Instalment::select('id', 'title', 'foreign_title', 'series_id', 'slug', 'cover_photo_path', 'is_new')
                ->whereIn('id', $instalmentIDs)
                ->where('is_publish', true)
                ->get();

                $series = Show::select('id', 'title', 'slug')->where('show_type_id', 2)->get();
                
                if(count($instalmentsArr) >= 1){
                    foreach($instalmentsArr as $instalment){
                        $serie = $series->where('id', $instalment->series_id)->first();
                        if($serie){
                            $instalment->slug = '/series/'.$serie->slug.'/'.$instalment->slug;
                            $data['instalment'][] = $instalment;
                            if($instalment->cover_photo_path){
                                if(Storage::disk('public')->exists($instalment->cover_photo_path)){
                                    $instalment->cover_photo_path = Storage::url($instalment->cover_photo_path);
                                }
                                else {
                                    $instalment->cover_photo_path = null;
                                }
                            }
                        }
                        
                    }
                }
            }

            return response()->json($data, 200);
        }
        else {
            return response()->json('This director was not found', 202);
        }
    }
}
