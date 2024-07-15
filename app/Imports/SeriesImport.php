<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Instalment;
use App\Models\Show;
use App\Models\ShowCountry;
use App\Models\ShowCast;
use App\Models\ShowDirector;
use App\Models\ShowGenre;
use App\Models\ShowLanguage;
use App\Models\Critic;
use App\Models\Region;
use App\Models\InstalmentDirector;
use App\Models\InstalmentLanguage;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Illuminate\Http\Request;

class SeriesImport implements ToCollection, WithHeadingRow
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

    public function collection(collection $rows)
    {
        $regionIDs = Region::all();
        foreach($rows as $row){
            if(trim($row['housemediaid']) == ""){
                request()->session()->put('import_series_error', 'House Media ID should not be empty!');
                return;
            }

            if(trim($row['titleenglish']) == ""){
                request()->session()->put('import_series_error', 'Title English should not be empty!');
                return;
            }

            if(trim($row['seriestitle']) == ""){
                request()->session()->put('import_series_error', 'Series Title should not be empty!');
                return;
            }

            if(trim($row['category']) == ""){
                request()->session()->put('import_series_error', 'Category should not be empty!');
                return;
            }

            if(trim($row['region']) == ""){
                request()->session()->put('import_series_error', 'Region should not be empty!');
                return;
            }

            if(trim($row['year']) == ""){
                request()->session()->put('import_series_error', 'Year should not be empty!');
                return;
            }

            if(trim($row['duration']) == ""){
                request()->session()->put('import_series_error', 'Duration should not be empty!');
                return;
            }

            if(trim($row['seriesslug']) == ""){
                request()->session()->put('import_series_error', 'Series Slug should not be empty!');
                return;
            }

            if(trim($row['slug']) == ""){
                request()->session()->put('import_series_error', 'Slug should not be empty!');
                return;
            }

            if(trim($row['odlogo']) == ""){
                request()->session()->put('import_series_error', 'OD Logo should not be empty!');
                return;
            }

            if(trim($row['instalmentnumber']) == ""){
                request()->session()->put('import_series_error', 'Instalment Number should not be empty!');
                return;
            }

            //$showType = $showTypeIDs->where('id', trim($row['category']))->first();
            //if(!$showType || $row['category'] !== 1 && $row['category'] !== 4){
            if($row['category'] !== 2){
                request()->session()->put('import_series_error', 'Incorrect category!');
                return;
            }

            $region = $regionIDs->where('id', trim($row['region']))->first();
            if(!$region){
                request()->session()->put('import_series_error', 'Incorrect region!');
                return;
            }   
        }


        foreach ($rows as $row)
        { 
            $show = Show::updateOrCreate(
                [
                    //'title' => $row['seriestitleforeign'],
                    //'translated_title' => $row['seriestitle'],
                    'slug' => $row['seriesslug']
                ],
                [
                    'title' => $row['seriestitle'],
                    'foreign_title' => $row['seriestitleforeign'],
                    'show_type_id' => $row['category'],
                    //'full_desc' => $row['blurbs'],
                    'region_id' => $row['region'],
                    'slug' => $row['seriesslug'],
                    //'short_desc' => $row['blurbs'],
                    //'is_publish' => true,
                    //'is_new' => false,
                    // 'trailer_link_url' => null,
                    // 'house_media_id' => null,
                    // 'release_year' => null,
                    // 'runtime' => null,
                    // 'expiry_date' => null,
                    // 'trivia_desc' => null,
                ]
            );

            if($show != null){
                ShowCountry::where('show_id', $show->id)->delete();
                if(trim($row['country1']) != ""){
                    ShowCountry::Create([
                        'show_id' => $show->id,
                        'country_id' => $row['country1'],
                    ]);
                }

                if(trim($row['country2']) != ""){
                    ShowCountry::Create([
                        'show_id' => $show->id,
                        'country_id' => $row['country2'],
                    ]);
                }

                if(trim($row['country3']) != ""){
                    ShowCountry::Create([
                        'show_id' => $show->id,
                        'country_id' => $row['country3'],
                    ]);
                }

                ShowGenre::where('show_id', $show->id)->delete();
                if(trim($row['genre1']) != ""){
                    ShowGenre::Create([
                        'show_id' => $show->id,
                        'genre_id' => $row['genre1'],
                    ]);
                }

                if(trim($row['genre2']) != ""){
                    ShowGenre::Create([
                        'show_id' => $show->id,
                        'genre_id' => $row['genre2'],
                    ]);
                }

                if(trim($row['genre3']) != ""){
                    ShowGenre::Create([
                        'show_id' => $show->id,
                        'genre_id' => $row['genre3'],
                    ]);
                }

                //$showGenres = ShowGenre::where('show_id', $show->id)->get();
                // $showGenres = ShowGenre::where('show_id', $show->id)->pluck('genre_id');
                
                // if(trim($row['genre1']) != ""){
                //     if($showGenres->has($row['genre1']) != true){
                //         ShowGenre::Create([
                //             'show_id' => $show->id,
                //             'genre_id' => $row['genre1'],
                //         ]);
                //     }
                // }

                // if(trim($row['genre2']) != ""){
                //     if($showGenres->has($row['genre2']) != true){
                //         ShowGenre::Create([
                //             'show_id' => $show->id,
                //             'genre_id' => $row['genre2'],
                //         ]);
                //     }
                // }

                // if(trim($row['genre3']) != ""){
                //     if($showGenres->has($row['genre3']) != true){
                //         ShowGenre::Create([
                //             'show_id' => $show->id,
                //             'genre_id' => $row['genre3'],
                //         ]);
                //     }
                // }

                ShowLanguage::where('show_id', $show->id)->delete();
                if(trim($row['language1']) != ""){
                    ShowLanguage::Create([
                        'show_id' => $show->id,
                        'language_id' => $row['language1'],
                    ]);
                }

                if(trim($row['language2']) != ""){
                    ShowLanguage::Create([
                        'show_id' => $show->id,
                        'language_id' => $row['language2'],
                    ]);
                }

                if(trim($row['language3']) != ""){
                    ShowLanguage::Create([
                        'show_id' => $show->id,
                        'language_id' => $row['language3'],
                    ]);
                }

                Critic::where('show_id', $show->id)->delete();
                if(trim($row['critic1']) != "" && trim($row['critic1name']) != ""){
                    Critic::Create([
                        'show_id' => $show->id,
                        'name' => $row['critic1name'],
                        'critique' => $row['critic1']
                    ]);
                }

                if(trim($row['critic2']) != "" && trim($row['critic2name']) != ""){
                    Critic::Create([
                        'show_id' => $show->id,
                        'name' => $row['critic2name'],
                        'critique' => $row['critic2']
                    ]);
                }

                if(trim($row['critic3']) != "" && trim($row['critic3name']) != ""){
                    Critic::Create([
                        'show_id' => $show->id,
                        'name' => $row['critic3name'],
                        'critique' => $row['critic3']
                    ]);
                }
            }

            $series = Show::where('slug', $row['seriesslug'])->first();
            if($series != null){
                $onDemand = true;
                if(trim($row['odlogo']) == "No"){
                    $onDemand = false;
                }

                $instalment = Instalment::updateOrCreate(
                    [
                        'house_media_id' => $row['housemediaid']
                    ],
                    [
                        'title' => $row['titleenglish'],
                        'foreign_title' => $row['titleforeign'],
                        'expiry_date' => trim($row['expirydate']) == ""? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['expirydate']),
                        'runtime' => $row['duration'],
                        'full_desc' => $row['fulldescription'],
                        'release_year' => $row['year'],
                        'trailer_link_url' => $row['promolink'],
                        'slug' => $row['slug'],
                        'short_desc' => $row['blurbs'],
                        'trivia_desc' => $row['trivia'],
                        'series_id' => $series->id,
                        'instalment_number' => $row['instalmentnumber'],
                        'on_demand' => $onDemand
                    ]
                );

                if($instalment != null){
                    InstalmentDirector::where('instalment_id', $instalment->id)->delete();
                    if(trim($row['director1']) != ""){
                        InstalmentDirector::Create([
                            'instalment_id' => $instalment->id,
                            'director_id' => $row['director1'],
                        ]);
                    }

                    if(trim($row['director2']) != ""){
                        InstalmentDirector::Create([
                            'instalment_id' => $instalment->id,
                            'director_id' => $row['director2'],
                        ]);
                    }

                    if(trim($row['director3']) != ""){
                        InstalmentDirector::Create([
                            'instalment_id' => $instalment->id,
                            'director_id' => $row['director3'],
                        ]);
                    }

                    if(trim($row['language1']) != ""){
                        InstalmentLanguage::Create([
                            'instalment_id' => $instalment->id,
                            'language_id' => $row['language1'],
                        ]);
                    }

                    if(trim($row['language2']) != ""){
                        InstalmentLanguage::Create([
                            'instalment_id' => $instalment->id,
                            'language_id' => $row['language2'],
                        ]);
                    }

                    if(trim($row['language3']) != ""){
                        InstalmentLanguage::Create([
                            'instalment_id' => $instalment->id,
                            'language_id' => $row['language3'],
                        ]);
                    }
                }
                
            }
            
        }
    }
}
