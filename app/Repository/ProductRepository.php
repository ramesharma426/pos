<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Product;
use App\Repository\Interfaces\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getStock($product_id)
    {
        return $this->model->select(['id', 'stock', 'per_unit_price'])->where('id', $product_id)->first();
    }

    public function getAllProducts(int $paginator, array $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->model
            ->with(['category' => fn($q) => $q->select(['id', 'name'])->withTrashed()])
            ->with(['unit' => fn($q) => $q->select(['id', 'name'])->withTrashed()])
            ->when(array_key_exists("search_term", $request), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->orWhere('name', 'LIKE', "%{$request['search_term']}%")
                        ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('name', 'LIKE', "%{$request['search_term']}%");
                        });
                });
            })->orderBy('created_at', 'desc')->paginate($paginator);
    }

    public function stockUpdate(array $orderedItems): bool
    {
        foreach ($orderedItems as $orderedItem) {
            $this->model->where('id', $orderedItem['product_id'])
                ->decrement('stock', $orderedItem['product_quantity'] * $orderedItem['order_quantity']);
        }

        return true;
    }

    public function productStockUpdate(array $data)
    {
        return $this->model
            ->where('id', $data['product_id'])
            ->update(['per_unit_price' => $data['per_unit_price'], 'stock' => $data['stock']]);
    }

}
