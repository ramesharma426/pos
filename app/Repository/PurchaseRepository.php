<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Purchase;
use App\Repository\Interfaces\PurchaseRepositoryInterface;

class PurchaseRepository extends BaseRepository implements PurchaseRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Purchase $model)
    {
        parent::__construct($model);
    }

    public function getAllPurchases(int $paginator){
        return $this->model
            ->with(['product.unit' => fn($q) => $q->select(['id','name'])->withTrashed()])
            ->with(['product' => fn($q) => $q->select(['id','name','unit_id'])->withTrashed()])
            ->orderBy('created_at', 'desc')
            ->paginate($paginator);
    }

}
