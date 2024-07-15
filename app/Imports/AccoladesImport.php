<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Accolade;
use App\Models\Show;
use App\Models\Instalment;
use App\Models\ShowAccolade;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;

class AccoladesImport implements ToCollection, WithHeadingRow
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

        foreach($rows as $row){
            if(trim($row['housemediaid']) == ""){
                request()->session()->put('import_accolades_error', 'House Media ID should not be empty!');
                return;
            }

            $show = $allShow->where('house_media_id', $row['housemediaid'])->first();
            $instalment = $allInstalment->where('house_media_id', $row['housemediaid'])->first();

            if($show == null && $instalment == null){
                request()->session()->put('import_accolades_error', 'House Media ID not found!');
                return;
            } 
        }

        foreach ($rows as $row)
        {
            $accolade1 = null;
            $accolade2 = null;
            $accolade3 = null;
            $accolade4 = null;
            $accolade5 = null;
            $show = null;
            // store accolade data to Accolade table
            if(trim($row['accoladename1']) != ""){
                $accolade1 = Accolade::updateOrCreate(
                    [
                        'category' => $row['accoladecategory1'],
                        'name' => $row['accoladename1']
                    ],
                    [
                        'category' => $row['accoladecategory1'],
                        'name' => $row['accoladename1'],
                    ]
                );
            }

            if(trim($row['accoladename2']) != ""){
                $accolade2 = Accolade::updateOrCreate(
                    [
                        'category' => $row['accoladecategory2'],
                        'name' => $row['accoladename2'],
                    ],
                    [
                        'category' => $row['accoladecategory2'],
                        'name' => $row['accoladename2'],
                    ]
                );
            }

            if(trim($row['accoladename3']) != ""){
                $accolade3 = Accolade::updateOrCreate(
                    [
                        'category' => $row['accoladecategory3'],
                        'name' => $row['accoladename3'],
                    ],
                    [
                        'category' => $row['accoladecategory3'],
                        'name' => $row['accoladename3'],
                    ]
                );
            }

            if(trim($row['accoladename4']) != ""){
                $accolade4 = Accolade::updateOrCreate(
                    [
                        'category' => $row['accoladecategory4'],
                        'name' => $row['accoladename4'],
                    ],
                    [
                        'category' => $row['accoladecategory4'],
                        'name' => $row['accoladename4'],
                    ]
                );
            }

            if(trim($row['accoladename5']) != ""){
                $accolade5 = Accolade::updateOrCreate(
                    [
                        'category' => $row['accoladecategory5'],
                        'name' => $row['accoladename5'],
                    ],
                    [
                        'category' => $row['accoladecategory5'],
                        'name' => $row['accoladename5'],
                    ]
                );
            }

            if(trim($row['housemediaid']) != ""){
                $show = $allShow->where('house_media_id', $row['housemediaid'])->first();
                if($show != null){
                    ShowAccolade::where('show_id', $show->id)->delete();
                    if($accolade1 != null){
                        ShowAccolade::Create([
                            'show_id' => $show->id,
                            'accolade_id' => $accolade1->id
                        ]);
                    }

                    if($accolade2 != null){
                        ShowAccolade::Create([
                            'show_id' => $show->id,
                            'accolade_id' => $accolade2->id
                        ]);
                    }

                    if($accolade3 != null){
                        ShowAccolade::Create([
                            'show_id' => $show->id,
                            'accolade_id' => $accolade3->id
                        ]);
                    }

                    if($accolade4 != null){
                        ShowAccolade::Create([
                            'show_id' => $show->id,
                            'accolade_id' => $accolade4->id
                        ]);
                    }

                    if($accolade5 != null){
                        ShowAccolade::Create([
                            'show_id' => $show->id,
                            'accolade_id' => $accolade5->id
                        ]);
                    }

                }
                
            }
        }
    }
}
