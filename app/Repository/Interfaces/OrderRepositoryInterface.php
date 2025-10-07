<?php

namespace App\Repository\Interfaces;

interface OrderRepositoryInterface
{
    public function getAllOrders(int $paginator, array $request);
}
