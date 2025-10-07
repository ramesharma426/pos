<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\OrderRepositoryInterface;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderItemService $orderItemService
    ) { }

    public function getAllOrders(int $paginator, array $request)
    {
        return $this->orderRepository->getAllOrders($paginator, $request);
    }

    /**
     * @throws ErrorPageException
     */
    public function orderStore(array|\Illuminate\Support\ValidatedInput $request): void
    {
        $data = [
            'customer_name' => $request['customer_name'],
            //'customer_address' => $request['customer_address'],
            //'customer_phone_number' => $request['customer_phone_number'],
            'table_id' => $request['table_id'],
            //'order_number' => uniqid(),
        ];
        if ($this->orderRepository->create($data))
            return;

        throw new ErrorPageException('Cannot Create new Order', 500);
    }


    /**
     * @throws ErrorPageException
     */
    public function orderUpdate(array|\Illuminate\Support\ValidatedInput $request): void
    {
        $data = [
            'customer_name' => $request['customer_name'],
            //'customer_address' => $request['customer_address'],
            //'customer_phone_number' => $request['customer_phone_number'],
            'table_id' => $request['table_id'],
        ];
        if ($this->orderRepository->update($request['id'], $data))
            return;

        throw new ErrorPageException('Cannot Update Unit', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function deleteOrder($order_id): void
    {
        if ($this->orderRepository->destroy($order_id) and $this->orderItemService->deleteOrderItems($order_id))
            return;

        throw new ErrorPageException('Cannot Remove Order', 500);
    }
}
