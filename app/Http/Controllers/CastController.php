<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Show;
use App\Models\ShowType;
use App\Models\Cast;
use App\Models\ShowCast;
use App\Models\InstalmentCast;
use App\Models\Instalment;

class CastController extends Controller
{
    public function getCast(Request $req){
        $cast = Cast::select('id', 'name', 'slug', 'description', 'profile_photo_path')
        ->where('slug', $req->slug)
        ->first();

        if($cast){

            $data = [];

            $data['id'] = $cast->id;
            $data['name'] = $cast->name;
            // $data['slug'] = $cast->slug;
            $data['title'] = 'Cast';
            $data['description'] = $cast->description;
            $data['meta_title'] = $cast->meta_title;
            $data['meta_description'] = $cast->meta_description;
            if($cast->profile_photo_path){
                if(Storage::disk('public')->exists($cast->profile_photo_path)){
                    $data['profile_photo_path'] = Storage::url($cast->profile_photo_path);
                }
                else {
                    $data['profile_photo_path'] = null;
                }
            }

            $showIDs = ShowCast::where('cast_id', $cast->id)->pluck('show_id');
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

            $instalmentIDs = InstalmentCast::where('cast_id', $cast->id)->pluck('instalment_id');
            if(count($instalmentIDs) >= 1){
                $instalmentsArr = Instalment::select('id', 'title', 'foreign_title', 'slug', 'series_id', 'cover_photo_path', 'is_new')
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
            return response()->json('This cast was not found', 202);
        }
    }
}
