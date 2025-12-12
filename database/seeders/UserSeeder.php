<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'chloe',
                'email' => 'chloe@ambercreative.sg',
                'password' => bcrypt('cinemanova5050')
            ],
            [
                'name' => 'jackie',
                'email' => 'jackie@ambercreative.sg',
                'password' => bcrypt('cinemanova5050')
            ],
            [
                'name' => 'chia shun',
                'email' => 'chiashun@ambercreative.sg',
                'password' => bcrypt('cinemanova5050')
            ],
            [
                'name' => 'corney',
                'email' => 'corney@ambercreative.sg',
                'password' => bcrypt('cinemanova5050')
            ]
        ];

        foreach($users as $user){
            User::create($user);
        }
    }
}
