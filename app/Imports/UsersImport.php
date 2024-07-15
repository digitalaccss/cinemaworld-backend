<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

//class UsersImport implements WithHeadingRow, WithUpserts, ToCollection
class UsersImport implements ToModel, WithHeadingRow, WithUpserts, ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password']),
        ]);

    }

    // public function collection(collection $rows)
    // {
    //     foreach($rows as $row){
    //         $user = User::upsert([
    //             ['name' => $row['name'], 'email' => $row['email'], 'password' => $row['password']]
    //         ], ['email']);
    //     }
        

    // }

    public function uniqueBy()
    {
        return 'email';
    }
}
