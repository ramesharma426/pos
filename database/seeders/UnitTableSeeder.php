<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['ml', 'ltr', 'kg', 'mg', 'pcs'];

        foreach ($data as $datum) {
            $role = new Unit();
            $role->name = $datum;
            $role->save();
        }
    }
}
