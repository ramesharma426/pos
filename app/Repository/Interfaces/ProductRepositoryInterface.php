<?php

namespace App\Repository\Interfaces;

interface ProductRepositoryInterface
{
    public function getAllProducts(int $paginator, array $request);
    public function stockUpdate(array $request);
    public function productStockUpdate(array $data);
}
