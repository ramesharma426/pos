<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['admin admin','admin@pos.com', 1],
            ['staff staff','staff@pos.com', 2],
        ];

        foreach($data as $datum){
            $user = new User();
            $user->name = $datum[0];
            $user->email = $datum[1];
            $user->role_id = $datum[2];
            $user->password = Hash::make('password');
            $user->save();
        }

    }
}
