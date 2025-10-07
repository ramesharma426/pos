<?php

namespace App\Service;

use App\Repository\Interfaces\SalesRepositoryInterface;

class SalesService
{
    public function __construct(private readonly SalesRepositoryInterface $salesRepository)
    {
    }

    public function recordDailySales(array $orderedItems): bool
    {
        return $this->salesRepository->recordDailySales($orderedItems);
    }

    public function getAllSales(array $request)
    {
        return $this->salesRepository->getAllSales($request);
    }

}
