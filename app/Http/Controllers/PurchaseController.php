<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorPageException;
use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Service\PurchaseService;
use App\Traits\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    use Messages;
    public function __construct(private readonly PurchaseService $purchaseService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->purchaseService->getAllPurchases(20)->withQueryString();
        return PurchaseResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->purchaseService->purchaseStore($request->validated());
            DB::commit();
            [$msg, $stc] = array($this->getMessage('Purchase Stored'), Response::HTTP_CREATED);
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
