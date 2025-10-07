<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=1; $i < 11; $i++){
            $t = new Table();
            $t->number = $i;
            $t->capacity = rand(2, 6);
            $t->save();
        }
    }
}
