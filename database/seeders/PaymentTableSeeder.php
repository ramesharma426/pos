<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Traits\Helpers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTableSeeder extends Seeder
{
    use Helpers;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=1; $i<11;$i++ ){
            $p = new Payment();
            $p->order_id = $i;
            $p->bill_number = $this->billNumberAG();
            $p->save();
        }
    }
}
