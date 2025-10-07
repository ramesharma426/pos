<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=0; $i < 50; $i++){
            $o = new Order();
            $o->customer_name = "customer $i";
            //$o->customer_address = "address $i";
            //$o->customer_phone_number = "986755542$i";
            //$o->order_number = "123hh342$i";
            $o->table_id = rand(1, 10);
            $o->save();

            $oiCount = rand(4, 7);
            for($j=0; $j < $oiCount; $j++){
                $p = ProductVariant::inRandomOrder()->first();
                $oi = new OrderItem();
                $oi->product_variant_id = $p->id;
                $oi->order_id = $o->id;
                $oi->quantity = rand(10, 50);
                $oi->rate = $p->rate;
                $oi->delivered_at = rand(true, false) ? now() : null;
                $oi->save();
            }
        }
    }
}
