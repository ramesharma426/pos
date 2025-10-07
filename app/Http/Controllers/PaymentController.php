<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountByDateRequest;
use App\Service\OrderItemService;
use App\Service\ProductService;
use App\Service\ProductVariantService;
use App\Service\SalesService;
use App\Traits\Messages;
use App\Service\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ErrorPageException;
use App\Http\Resources\PaymentResource;
use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentSearchRequest;
use App\Http\Resources\BillDetailResource;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    use Messages;

    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaymentSearchRequest $request)
    {
        $products = $this->paymentService->getAllPayments(20, $request->validated())->withQueryString();
        return PaymentResource::collection($products);

    }

    public function discountByDate(DiscountByDateRequest $request){
        $discount = $this->paymentService->getTotalDiscountByDate($request->validated());
        return response()->json(['discount' => $discount]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentStoreRequest   $request,
                          OrderItemService      $orderItemService,
                          ProductVariantService $productVariantService,
                          ProductService        $productService,
                          SalesService          $salesService)
    {
        try {
            DB::beginTransaction();
            $payment = $this->paymentService->paymentStore($request->validated());
            $productVariantService->deductStockAddSales($request->validated(), $orderItemService, $productService, $salesService);
            DB::commit();
            [$msg, $stc] = array(['message' => 'Payment Complete', 'bill_number' => $payment->bill_number, 'bill_date' => $payment->created_at->format('Y-m-d')], Response::HTTP_CREATED);
        } catch (ErrorPageException $e) {
            DB::rollBack();
            [$msg, $stc] = array($this->getErrorMessage($e->getMessage()), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exp) {
            DB::rollBack();
            dd($exp);
            Log::alert($exp->getMessage());
            if($exp->getCode() == 22003)
                [$msg, $stc] = array($this->getErrorMessage('Insufficient Stock quantity'), Response::HTTP_UNPROCESSABLE_ENTITY);
            else
                [$msg, $stc] = array($this->getErrorMessage('Something Went wrong !!!'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($msg, $stc);
    }

    public function reprint($order_id)
    {
        $billDetails = $this->paymentService->getBillDetails($order_id);
        return BillDetailResource::collection($billDetails);
    }

}
