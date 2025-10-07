<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\ProductVariant;
use App\Repository\Interfaces\ProductVariantRepositoryInterface;

class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(ProductVariant $model)
    {
        parent::__construct($model);
    }

    public function getAllProductVariants(int $paginator, array $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->model
            ->with(['product' => fn($q) => $q->select(['id','name'])->withTrashed()])
            ->when(array_key_exists("search_term", $request), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->orWhere('name', 'LIKE', "%{$request['search_term']}%")
                        ->orWhereHas('product', function ($q) use ($request) {
                            $q->where('name', 'LIKE', "%{$request['search_term']}%");
                        });
                });
            })->orderBy('created_at', 'desc')->paginate($paginator);
    }

        public function getProductVariantRate($product_variant_id)
    {
        return $this->model->select('rate')->where('id', $product_variant_id)->first()->rate;
    }

}
