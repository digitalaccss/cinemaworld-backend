<?php

namespace App\Imports;

use App\Models\Schedule;
use App\Models\Show;
use App\Models\Instalment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Illuminate\Http\Request;

class SchedulesImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    use WithConditionalSheets;

    public function conditionalSheets(): array
    {
        return [
            'Worksheet 1' => new FirstSheetImport()
        ];
    }

    public function collection(Collection $rows)
    {
        $allShow = Show::all();
        $allInstalment = Instalment::all();
        $date = collect();

        foreach($rows as $row){
           
          // if the row is empty, then break the foreach
          if(trim($row['date']) == "" && trim($row['starttime']) == "" && trim($row['endtime']) == "" && trim($row['housemediaid']) == ""){
            break;
          }

          // check whether the row missing any data
          if(trim($row['date']) == ""){
            request()->session()->put('import_schedule_error', 'Date should not be empty!');
            return;
          }

          if(trim($row['starttime']) == ""){
            request()->session()->put('import_schedule_error', 'Start Time should not be empty!');
            return;
          }

          if(trim($row['endtime']) == ""){
            request()->session()->put('import_schedule_error', 'End Time should not be empty!');
            return;
          }

          if(trim($row['housemediaid']) == ""){
            request()->session()->put('import_schedule_error', 'House Media ID should not be empty!');
            return;
          }

          $show = $allShow->where('house_media_id', $row['housemediaid'])->first();
          $instalment = $allInstalment->where('house_media_id', $row['housemediaid'])->first();

          if($show == null && $instalment == null){
            request()->session()->put('import_schedule_error', 'House Media ID not found!');
            return;
          }

          $date->push(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']))->unique();
        }
        
        $duplicateSchedule = Schedule::whereIn('schedule_start_date', $date)->delete();
        //$duplicateSchedule = Schedule::where('schedule_start_date', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']))->delete();

        foreach ($rows as $row)
        {
            //$show = Show::where('house_media_id', $row['housemediaid'])->first();
            //$instalment = Instalment::where('house_media_id', $row['housemediaid'])->first();

            // if the row is empty, then break the foreach
            if(trim($row['date']) == "" && trim($row['starttime']) == "" && trim($row['endtime']) == "" && trim($row['housemediaid']) == ""){
              break;
            }

            $show = $allShow->where('house_media_id', $row['housemediaid'])->first();
            $instalment = $allInstalment->where('house_media_id', $row['housemediaid'])->first();

            $showId = $show == null? $instalment == null? null : $instalment->series_id : $show->id;
            if($showId != null && trim($row['housemediaid']) != null){
                $createSchedule = Schedule::create([
                    'schedule_start_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']),
                    'schedule_start_time' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['starttime']),
                    'schedule_end_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']),
                    'schedule_end_time' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['endtime']),
                    //'show_id' => $show == null? $instalment == null? null : $instalment->series_id : $show->id,
                    'show_id' => $showId,
                    'instalment_id' => $instalment == null? null : $instalment->id,
                ]);
            }
        }
    }
}
