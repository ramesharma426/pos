<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStockUpdateRequest;
use App\Traits\Messages;
use App\Service\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ErrorPageException;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductSearchRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductNameResource;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use Messages;

    public function __construct(private readonly ProductService $productService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProductSearchRequest $request)
    {
        $products = $this->productService->getAllProducts(20, $request->validated())->withQueryString();
        return ProductResource::collection($products);
    }

    /**
     * @throws ErrorPageException
     */
    public function productName()
    {
        $products = $this->productService->getAllProductName();
        return ProductNameResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->productService->productStore($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getSuccessMessage('Product'), Response::HTTP_CREATED);
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

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->productService->productUpdate($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getUpdateMessage('Product'), Response::HTTP_CREATED);
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

//    public function updateStock(ProductStockUpdateRequest $request)
//    {
//        try {
//            DB::beginTransaction();
//            $this->productService->productStockUpdate($request->validated());
//            DB::commit();
//            [$msg, $stc] = array($this->getMessage('updated successfully'), Response::HTTP_CREATED);
//        } catch (ErrorPageException $e) {
//            DB::rollBack();
//            [$msg, $stc] = array($this->getErrorMessage($e->getMessage()), Response::HTTP_UNPROCESSABLE_ENTITY);
//        } catch (\Exception $exp) {
//            DB::rollBack();
//            if($exp->getCode() == 22003 )
//                [$msg, $stc] = array($this->getErrorMessage('Invalid Operation'), Response::HTTP_UNPROCESSABLE_ENTITY);
//            else
//                [$msg, $stc] = array($this->getErrorMessage('Something Went wrong !!!'), Response::HTTP_UNPROCESSABLE_ENTITY);
//        }
//        return response()->json($msg, $stc);
//    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id)
    {
        try {
            DB::beginTransaction();
            $this->productService->deleteProduct($product_id);
            DB::commit();
            [$msg, $stc] = array($this->getDestroyMessage('Product'), Response::HTTP_CREATED);
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
