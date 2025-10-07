<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $data = [
//            ['/storage/test-test/1.jpg','Veg momo', 1 , 1, 'veg_momo'],
//            ['/storage/test-test/2.jpg','paneer momo', 1 , 1, 'paneer_momo'],
//            ['/storage/test-test/3.jpg','chicken momo', 1 , 1, 'chicken_momo'],
//            ['/storage/test-test/4.jpg','Veg chowmin', 2 , 2, 'veg_chowmin'],
//            ['/storage/test-test/5.jpg','paneer chowmin', 2 , 2, 'paneer_chowmin'],
//            ['/storage/test-test/6.jpg','chicken chowmin', 2 , 2, 'chicken_chowmin'],
//            ['/storage/test-test/7.jpg','coke', 3 , 3, 'coke'],
//            ['/storage/test-test/8.jpg','pepsi', 3 , 3, 'pepsi'],
//            ['/storage/test-test/9.jpg','fanta', 3 , 3, 'fanta'],
//            ['/storage/test-test/10.jpg','wine', 4 , 4, 'wine'],
//            ['/storage/test-test/11.jpg','vodka', 4 , 4, 'vodka'],
//            ['/storage/test-test/12.jpg','rum', 4 , 4, 'rum'],
//        ];

        $data = [
            ['veg momo', 5, 1],
            ['chicken momo', 5, 1],
            ['veg chowmin', 5, 2],
            ['chicken chowmin', 5, 2],
            ['coke', 1, 3],
            ['pepsi', 1, 3],
            ['fanta', 1, 3],
            ['wine', 1, 4],
            ['vodka', 1, 4],
            ['rum', 1, 4]
        ];

        foreach($data as $datum){
            $p = new Product();
            //$p->image_url = $datum[0];
            $p->name = $datum[0];
            $p->unit_id = $datum[1];
            $p->category_id = $datum[2];
            $p->stock = 100000;
            $p->per_unit_price = 5;
            $p->save();
        }
    }
}
