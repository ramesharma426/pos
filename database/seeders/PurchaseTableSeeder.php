<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(range(1,30) as $i){
            $purchase = new Purchase();
            $purchase->product_id = rand(1,10);
            $purchase->quantity = rand(1,30);
            $purchase->cost = rand(1000, 30000);
            $purchase->save();
        }
    }
}
