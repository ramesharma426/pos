<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\PurchaseRepositoryInterface;

class PurchaseService
{
    public function __construct(private readonly PurchaseRepositoryInterface $purchaseRepository,
                                private readonly ProductService $productService)
    {
    }

    /**
     * @throws ErrorPageException
     */
    public function purchaseStore(array $request): void
    {
        $data = [
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'cost' => $request['cost'],
        ];

        $productStock = $this->productService->getStock($request['product_id']);

        $currentStockTotalCost = $productStock->stock * $productStock->per_unit_price;
        $totalStock = $request['quantity'] + $productStock->stock;

        //formula =  oldStockCost + newStockCost / oldStockQuantity + newStockQuantity
        $per_unit_price = ( $currentStockTotalCost + $request['cost']) / $totalStock;

        $productUpdateParameter = ['product_id' => $request['product_id'], 'per_unit_price' => $per_unit_price, 'stock' => $totalStock ];
        if($this->productService->productStockUpdate($productUpdateParameter) and $this->purchaseRepository->create($data))
            return;

        throw new ErrorPageException('Cannot Create new Purchase', 500);
    }

    public function getAllPurchases(int $paginator)
    {
        return $this->purchaseRepository->getAllPurchases($paginator);
    }


}
