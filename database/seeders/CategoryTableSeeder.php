<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['momo', 'chowmin', 'soft drinks', 'liquor', 'mutton', 'turkey',
            'beverage', 'dal', 'veg curry', 'rice', 'egg', 'non veg snack',
            'veg snack', ];

        forEach($data as $datum){
            $category = new Category();
            $category->name = $datum;
            $category->save();
        }
    }
}
