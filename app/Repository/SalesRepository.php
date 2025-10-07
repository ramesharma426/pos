<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Sales;
use App\Repository\Interfaces\SalesRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SalesRepository extends BaseRepository implements SalesRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Sales $model)
    {
        parent::__construct($model);
    }

    public function getAllSales(array $request)
    {
        return $this->model
            ->with(['product_variant' => fn($q) => $q->select(['id','name'])->withTrashed()])
            ->when(array_key_exists("date", $request), function ($q) use ($request) {
                $q->whereDate('created_at', 'LIKE', "%{$request['date']}%");
            })
            ->when(!array_key_exists("date", $request), function ($q) {
                $q->whereDate('created_at', 'LIKE', date('Y-m-d'));
            })
            ->get();
    }

    public function recordDailySales(array $orderedItems)
    {
        foreach ($orderedItems as $orderedItem) {

            $salesPrice = $orderedItem['order_quantity'] * $orderedItem['rate'];
            $costPrice = $orderedItem['per_unit_price'] * $orderedItem['order_quantity'] * $orderedItem['product_quantity'];

            $this->model->whereDate('created_at', date('Y-m-d'))->updateOrCreate(
                ['product_variant_id' => $orderedItem['product_variant_id']],
                [
                    'quantity' => DB::raw("quantity + " . $orderedItem['order_quantity']),
                    'sales_price' => DB::raw("sales_price + $salesPrice"),
                    'cost_price' => DB::raw("cost_price + $costPrice")
                ],
            );

        }

        return true;
    }

}
