<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Order;
use App\Repository\Interfaces\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getAllOrders(int $paginator, array $request)
    {
        return $this->model
            ->with(['table' => fn($q) => $q->select('id','number')->withTrashed()])
            ->with('payment')
            ->when(array_key_exists("search_term", $request), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->orWhere('customer_name', 'LIKE', "%{$request['search_term']}%")
                        ->orWhere('id', 'LIKE', "%{$request['search_term']}%")
                        ->orWhereHas('payment', function ($q) use ($request) {
                            $q->where('bill_number', 'LIKE', "%{$request['search_term']}%");
                        });
                });
            })->orderBy('created_at', 'desc')->paginate($paginator);
    }
}
