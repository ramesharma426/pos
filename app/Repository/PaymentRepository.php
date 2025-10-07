<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Payment;
use App\Repository\Interfaces\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function getAllPayments(int $paginator, array $request)
    {
        return $this->model
            ->when(array_key_exists("search_term", $request), function ($q) use ($request) {
                $q->where('bill_number', 'LIKE', "%{$request['search_term']}%");
            })->orderBy('created_at', 'desc')->paginate($paginator);
    }

    public function getBillDetails(int $order_id)
    {
        return $this->model->with('order.table')->where('order_id', $order_id)->get();
    }

    public function getTotalDiscountByDate(array $request)
    {
        return $this->model
            ->when(!array_key_exists('date', $request), function ($q) {
                $q->whereDate('created_at', 'LIKE', date('Y-m-d'));
            })
            ->when(array_key_exists('date', $request), function ($q) use ($request) {
                $q->whereDate('created_at', 'LIKE', "%{$request['date']}%");
            })
            ->sum('discount');
    }
}
