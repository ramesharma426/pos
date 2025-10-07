<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\OrderItemRepositoryInterface;

class OrderItemService
{
    public function __construct(private readonly OrderItemRepositoryInterface $orderItemRepository,
                                private readonly ProductVariantService $productVariantService)
    {
    }

    /**
     * @throws ErrorPageException
     */
    public function orderItemStore(array|\Illuminate\Support\ValidatedInput $request): void
    {
        $delivered_at = $request['delivered_at'] ? now() : null;

        $data = [
            'product_variant_id' => $request['product_variant_id'],
            'order_id' => $request['order_id'],
            'quantity' => $request['quantity'],
            'delivered_at' => $delivered_at,
            'rate' => $this->productVariantService->getProductVariantRate($request['product_variant_id'])
        ];

        if ($this->orderItemRepository->orderItemStore($data))
            return;

        throw new ErrorPageException('Cannot create order item', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function deliverOrderItemUpdate($orderItem_id): void
    {
        if($this->orderItemRepository->update($orderItem_id, ['delivered_at' => now()]))
            return;

        throw new ErrorPageException('Cannot Deliver Item', 500);
    }

    public function getAllOrderItems($order_id){
        return $this->orderItemRepository->getAllOrderItems($order_id);
    }

    public function cancelOrder($orderItem_id){
        if($this->orderItemRepository->destroy($orderItem_id))
            return;

        throw new ErrorPageException('Cannot remove order item', 500);
    }

    public function deleteOrderItems($order_id){
        if($this->orderItemRepository->deleteOrderItems($order_id)){
            return true;
        }

        throw new ErrorPageException('Cannot delete order items', 500);
    }

}
