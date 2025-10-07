<?php

namespace App\Repository\Interfaces;

interface PaymentRepositoryInterface
{

    public function getAllPayments(int $paginator, array $request);

    public function getTotalDiscountByDate(array $request);
}
