<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ScheduleFile;

class ScheduleFileController extends Controller
{
    public function getScheduleFile(Request $req){
        //if($req->has('year') && $req->has('month')){
            $scheduleFile = ScheduleFile::select('id', 'year', 'month', 'file_path', 'created_at')
            ->where('year', date('Y'))
            ->where('month', date('m'))
            // ->where('year', $req->year)
            // ->where('month', $req->month)
            ->first();

            if($scheduleFile){
                $data = [];
                $data['id'] = $scheduleFile->id;
                $data['year'] = $scheduleFile->year;
                $data['month'] = $scheduleFile->month;
                //$data[$indexPos]['file_path'] = $scheduleFile->file_path;
                //$data[$indexPos]['created_at'] = $scheduleFile->created_at;
                
                if($scheduleFile->file_path){
                    if(Storage::disk('public')->exists($scheduleFile->file_path)){
                        $scheduleFile->file_path = Storage::url($scheduleFile->file_path);
                    }
                    else {
                        $scheduleFile->file_path = null;
                    }
                    $data['file_path'] = $scheduleFile->file_path;
                }

                return response()->json($data, 200);
            }
            else {
                return response()->json('No schedule were found', 202);
            }
        //}else{
            //return response()->json('Please select year and month', 202);
        //}
        
    }
}
