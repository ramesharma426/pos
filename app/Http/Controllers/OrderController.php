<?php

namespace App\Http\Controllers;

use App\Traits\Messages;
use App\Service\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;
use App\Exceptions\ErrorPageException;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderSearchRequest;
use App\Http\Requests\OrderUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use Messages;
    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(OrderSearchRequest $request)
    {
        $orders = $this->orderService->getAllOrders(20, $request->validated())->withQueryString();
        return OrderResource::collection($orders);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->orderService->orderStore($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getSuccessMessage('Order'), Response::HTTP_CREATED);
        } catch (ErrorPageException $e){
            DB::rollBack();
            [$msg, $stc] = array($this->getErrorMessage($e->getMessage()), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exp) {
            DB::rollBack();
            Log::alert($exp->getMessage());
            [$msg, $stc] = array($this->getErrorMessage('Something Went wrong !!!'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($msg, $stc);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->orderService->orderUpdate($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getUpdateMessage('Order'), Response::HTTP_CREATED);
        } catch (ErrorPageException $e){
            DB::rollBack();
            [$msg, $stc] = array($this->getErrorMessage($e->getMessage()), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exp) {
            DB::rollBack();
            Log::alert($exp->getMessage());
            [$msg, $stc] = array($this->getErrorMessage('Something Went wrong !!!'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($msg, $stc);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($order_id)
    {
        try{
            DB::beginTransaction();
            $this->orderService->deleteOrder($order_id);
            DB::commit();
            [$msg, $stc] = array($this->getDestroyMessage('Order'), Response::HTTP_CREATED);
        } catch (ErrorPageException $e){
            DB::rollBack();
            [$msg, $stc] = array($this->getErrorMessage($e->getMessage()), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(\Exception $exp){
            DB::rollBack();
            Log::alert($exp->getMessage());
            [$msg, $stc] = array($this->getErrorMessage('Something Went wrong !!!'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($msg, $stc);
    }
}
