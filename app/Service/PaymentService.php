<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\PaymentRepositoryInterface;
use App\Traits\Helpers;

class PaymentService
{
    use Helpers;

    public function __construct(private readonly PaymentRepositoryInterface $paymentRepository)
    {
    }

    /**
     * @throws ErrorPageException
     */
    public function paymentStore(\Illuminate\Support\ValidatedInput|array $request)
    {
        if ($this->paymentRepository->fieldExists('order_id', $request['order_id']))
            throw new ErrorPageException('Payment Already Exists for this Order', 500);

        $data = [
            'order_id' => $request['order_id'],
            'bill_number' => $this->billNumberAG(),
            'discount' => $request['discount']
        ];

        $payment = $this->paymentRepository->create($data);
        if ($payment) return $payment;

        throw new ErrorPageException('Cannot be billed', 500);

    }

    public function getAllPayments(int $paginator, array $request)
    {
        return $this->paymentRepository->getAllPayments($paginator, $request);
    }

    public function getBillDetails(int $order_id)
    {
        return $this->paymentRepository->getBillDetails($order_id);
    }

    public function getTotalDiscountByDate(array $request){
        return $this->paymentRepository->getTotalDiscountByDate($request);
    }


}
