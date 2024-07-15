<?php

namespace App\Imports;

use App\Models\Show;
use App\Models\ShowCountry;
use App\Models\ShowCast;
use App\Models\ShowDirector;
use App\Models\ShowGenre;
use App\Models\ShowLanguage;
use App\Models\Critic;
use App\Models\Region;
use App\Models\ShowType;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

//class ShowsImport implements ToCollection, WithHeadingRow, WithValidation
class ShowsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use WithConditionalSheets;

    public function conditionalSheets(): array
    {
        return [
            'Worksheet 1' => new FirstSheetImport()
        ];
    }

    public function collection(collection $rows){
        $showTypeIDs = ShowType::all();
        $regionIDs = Region::all();

        foreach($rows as $row){
            if(trim($row['housemediaid']) == ""){
                request()->session()->put('import_show_error', 'House Media ID should not be empty!');
                return;
            }

            if(trim($row['titleenglish']) == ""){
                request()->session()->put('import_show_error', 'Title English should not be empty!');
                return;
            }

            if(trim($row['category']) == ""){
                request()->session()->put('import_show_error', 'Category should not be empty!');
                return;
            }

            if(trim($row['region']) == ""){
                request()->session()->put('import_show_error', 'Region should not be empty!');
                return;
            }

            if(trim($row['slug']) == ""){
                request()->session()->put('import_show_error', 'Slug should not be empty!');
                return;
            }

            if(trim($row['duration']) == ""){
                request()->session()->put('import_show_error', 'Duration should not be empty!');
                return;
            }

            if(trim($row['year']) == ""){
                request()->session()->put('import_show_error', 'Year should not be empty!');
                return;
            }

            if(trim($row['odlogo']) == ""){
                request()->session()->put('import_show_error', 'OD Logo should not be empty!');
                return;
            }

            $showType = $showTypeIDs->where('id', trim($row['category']))->first();
            if(!$showType || $row['category'] != 1 && $row['category'] != 3 && $row['category'] != 4){
                request()->session()->put('import_show_error', 'Incorrect category!');
                return;
            }

            $region = $regionIDs->where('id', trim($row['region']))->first();
            if(!$region){
                request()->session()->put('import_show_error', 'Incorrect region!');
                return;
            }   
        }

        foreach ($rows as $row)
        {
            $onDemand = true;
            if(trim($row['odlogo']) == "No"){
                $onDemand = false;
            }

            $show = Show::updateOrCreate(
                [
                    'house_media_id' => trim($row['housemediaid'])
                ],
                [
                    'title' => trim($row['titleenglish']),
                    'foreign_title' => $row['titleforeign'],
                    'show_type_id' => trim($row['category']),
                    'expiry_date' =>$row['expirydate'] == null ? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['expirydate'])),
                    'runtime' => $row['duration'],
                    //'full_desc' => $row['blurbs'],
                    'release_year' => $row['year'],
                    'region_id' => trim($row['region']),
                    'trailer_link_url' => $row['promolink'],
                    'trivia_desc' => $row['trivia'],
                    'slug' => trim($row['slug']),
                    'short_desc' => $row['blurbs'],
                    'full_desc' => $row['fulldescription'],
                    'on_demand' => $onDemand,
                    //'is_publish' => true,
                    //'is_new' => false,
                ]
            );
            ShowCountry::where('show_id', $show->id)->delete();
            if(trim($row['country1']) != ""){
                ShowCountry::Create([
                    'show_id' => $show->id,
                    'country_id' => trim($row['country1']),
                ]);
            }

            if(trim($row['country2']) != ""){
                ShowCountry::Create([
                    'show_id' => $show->id,
                    'country_id' => trim($row['country2']),
                ]);
            }

            if(trim($row['country3']) != ""){
                ShowCountry::Create([
                    'show_id' => $show->id,
                    'country_id' => trim($row['country3']),
                ]);
            }

            ShowDirector::where('show_id', $show->id)->delete();
            if(trim($row['director1']) != ""){
                ShowDirector::Create([
                    'show_id' => $show->id,
                    'director_id' => trim($row['director1']),
                ]);
            }

            if(trim($row['director2']) != ""){
                ShowDirector::Create([
                    'show_id' => $show->id,
                    'director_id' => trim($row['director2']),
                ]);
            }

            ShowGenre::where('show_id', $show->id)->delete();
            if(trim($row['genre1']) != ""){
                ShowGenre::Create([
                    'show_id' => $show->id,
                    'genre_id' => trim($row['genre1']),
                ]);
            }

            if(trim($row['genre2']) != ""){
                ShowGenre::Create([
                    'show_id' => $show->id,
                    'genre_id' => trim($row['genre2']),
                ]);
            }

            if(trim($row['genre3']) != ""){
                ShowGenre::Create([
                    'show_id' => $show->id,
                    'genre_id' => trim($row['genre3']),
                ]);
            }

            ShowLanguage::where('show_id', $show->id)->delete();
            if(trim($row['language1']) != ""){
                ShowLanguage::Create([
                    'show_id' => $show->id,
                    'language_id' => trim($row['language1']),
                ]);
            }

            if(trim($row['language2']) != ""){
                ShowLanguage::Create([
                    'show_id' => $show->id,
                    'language_id' => trim($row['language2']),
                ]);
            }

            if(trim($row['language3']) != ""){
                ShowLanguage::Create([
                    'show_id' => $show->id,
                    'language_id' => trim($row['language3']),
                ]);
            }

            Critic::where('show_id', $show->id)->delete();
            if(trim($row['critic1']) != "" && trim($row['critic1name']) != ""){
                Critic::Create([
                    'show_id' => $show->id,
                    'name' => trim($row['critic1name']),
                    'critique' => trim($row['critic1'])
                ]);
            }

            if(trim($row['critic2']) != "" && trim($row['critic2name']) != ""){
                Critic::Create([
                    'show_id' => $show->id,
                    'name' => trim($row['critic2name']),
                    'critique' => trim($row['critic2'])
                ]);
            }

            if(trim($row['critic3']) != "" && trim($row['critic3name']) != ""){
                Critic::Create([
                    'show_id' => $show->id,
                    'name' => trim($row['critic3name']),
                    'critique' => trim($row['critic3'])
                ]);
            }
            
        }
    }
}
