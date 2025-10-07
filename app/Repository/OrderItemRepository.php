<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\OrderItem;
use App\Repository\Interfaces\OrderItemRepositoryInterface;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(OrderItem $model)
    {
        parent::__construct($model);
    }

    public function getAllOrderItems($order_id)
    {
        return $this->model
            ->with(['product_variant.product' => fn($q) => $q->select(['id','per_unit_price'])->withTrashed()])
            ->with(['product_variant' => fn($q) => $q->withTrashed()])
            ->where('order_id', $order_id)
            ->get();
    }

    public function orderItemStore($request)
    {
        if ($this->model->where('product_variant_id', $request['product_variant_id'])->where('order_id', $request['order_id'])->exists())
            return $this->model
                ->where('product_variant_id', $request['product_variant_id'])
                ->where('order_id', $request['order_id'])
                ->increment('quantity', $request['quantity'], ['delivered_at' => $request['delivered_at']]);
        else return $this->create($request);
    }

    public function deleteOrderItems($order_id)
    {
        if (!$this->fieldExists('order_id', $order_id))
            return true;

        return $this->model->where('order_id', $order_id)->delete();
    }
}
