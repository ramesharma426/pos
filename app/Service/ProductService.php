<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\ProductRepositoryInterface;
use App\Traits\Helpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductService
{


    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function getAllProducts(int $paginator, array $request)
    {
        return $this->productRepository->getAllProducts($paginator, $request);
    }

    /**
     * @throws ErrorPageException
     */
    public function getAllProductName(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->productRepository->all(['id', 'name']);
    }

    public function getStock($product_id)
    {
        return $this->productRepository->getStock($product_id);
    }

    /**
     * @throws ErrorPageException
     */
    public function productStore(array|\Illuminate\Support\ValidatedInput $request)
    {
        $data = [
            'name' => $request['name'],
            'unit_id' => $request['unit_id'],
            'category_id' => $request['category_id'],
            'stock' => 0,
            'per_unit_price' => 0
        ];

        $response = $this->productRepository->create($data);
        if ($response->wasRecentlyCreated)
            return $response;

        throw new ErrorPageException('Cannot Create new Product', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function productUpdate(\Illuminate\Support\ValidatedInput|array $request): void
    {
        $data = [
            'name' => $request['name'],
            'unit_id' => $request['unit_id'],
            'category_id' => $request['category_id'],
        ];

        if ($this->productRepository->update($request['id'], $data))
            return;

        throw new ErrorPageException('Cannot Update Product', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function productStockUpdate(array $data): bool
    {
        if ($this->productRepository->productStockUpdate($data))
            return true;

        throw new ErrorPageException('Cannot update Stock', 500);
    }

    public function stockUpdate(array $orderedItems)
    {
        return $this->productRepository->stockUpdate($orderedItems);
    }

    /**
     * @throws ErrorPageException
     */
    public function deleteProduct($product_id): void
    {
        if ($this->productRepository->destroy($product_id))
            return;

        throw new ErrorPageException('Cannot Remove Product', 500);
    }
}
