<?php

namespace App\Repository\Interfaces;

interface PurchaseRepositoryInterface
{
    public function getAllPurchases(int $paginator);
}
