<?php

namespace App\Http\Controllers;

use App\Traits\Messages;
use App\Service\OrderItemService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ErrorPageException;
use App\Http\Requests\OrderItemStoreRequest;
use App\Http\Resources\OrderItemResource;
use Symfony\Component\HttpFoundation\Response;

class OrderItemController extends Controller
{
    use Messages;

    public function __construct(private readonly OrderItemService $orderItemService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderItemStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->orderItemService->orderItemStore($request->validated());
            DB::commit();
            [$msg, $stc] = array($this->getMessage('Item added'), Response::HTTP_CREATED);
        } catch (ErrorPageException $e) {
            DB::rollBack();
            [$msg, $stc] = array($this->getErrorMessage($e->getMessage()), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exp) {
            DB::rollBack();
            Log::alert($exp->getMessage());
            [$msg, $stc] = array($this->getErrorMessage('Something Went wrong !!!'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($msg, $stc);
    }

    public function deliverOrderItem($orderItem_id)
    {
        try {
            DB::beginTransaction();
            $this->orderItemService->deliverOrderItemUpdate($orderItem_id);
            DB::commit();
            [$msg, $stc] = array($this->getMessage('Item added to delivered list'), Response::HTTP_CREATED);
        } catch (ErrorPageException $e) {
            DB::rollBack();
            [$msg, $stc] = array($this->getErrorMessage($e->getMessage()), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exp) {
            DB::rollBack();
            Log::alert($exp->getMessage());
            [$msg, $stc] = array($this->getErrorMessage('Something Went wrong !!!'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($msg, $stc);
    }

    public function orderItems($order_id)
    {
        $orderItems = $this->orderItemService->getAllOrderItems($order_id);
        return OrderItemResource::collection($orderItems);
    }

    public function cancelOrderItem($orderItem_id)
    {
        try {
            DB::beginTransaction();
            $this->orderItemService->cancelOrder($orderItem_id);
            DB::commit();
            [$msg, $stc] = array($this->getMessage('Order Item removed'), Response::HTTP_CREATED);
        } catch (ErrorPageException $e) {
            DB::rollBack();
            [$msg, $stc] = array($this->getErrorMessage($e->getMessage()), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exp) {
            DB::rollBack();
            Log::alert($exp->getMessage());
            [$msg, $stc] = array($this->getErrorMessage('Something Went wrong !!!'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($msg, $stc);
    }
}
