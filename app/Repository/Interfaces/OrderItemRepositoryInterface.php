<?php

namespace App\Repository\Interfaces;

interface OrderItemRepositoryInterface
{
    public function getAllOrderItems($order_id);
    public function orderItemStore($request);
    public function deleteOrderItems($order_id);
}
