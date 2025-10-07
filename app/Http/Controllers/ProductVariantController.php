<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorPageException;
use App\Http\Requests\ProductVariantSearchRequest;
use App\Http\Requests\ProductVariantStoreRequest;
use App\Http\Requests\ProductVariantUpdateRequest;
use App\Http\Resources\ProductVariantNameResource;
use App\Http\Resources\ProductVariantResource;
use App\Service\ProductVariantService;
use App\Traits\Messages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductVariantController extends Controller
{
    use Messages;

    public function __construct(private readonly ProductVariantService $productVariantService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProductVariantSearchRequest $request)
    {
        $product_variants = $this->productVariantService->getAllProductVariants(20, $request->validated())->withQueryString();
        return ProductVariantResource::collection($product_variants);
    }

    /**
     * @throws ErrorPageException
     */
    public function productVariantName()
    {
        $productVariants = $this->productVariantService->getAllProductVariantName();
        return ProductVariantNameResource::collection($productVariants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductVariantStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->productVariantService->productVariantStore($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getSuccessMessage('Product Variant'), Response::HTTP_CREATED);
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
    public function update(ProductVariantUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->productVariantService->productVariantUpdate($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getUpdateMessage('Product Variant'), Response::HTTP_CREATED);
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
     * Remove the specified resource from storage.
     */
    public function destroy($product_variant_id)
    {
        try {
            DB::beginTransaction();
            $this->productVariantService->deleteProductVariant($product_variant_id);
            DB::commit();
            [$msg, $stc] = array($this->getDestroyMessage('Product Variant'), Response::HTTP_CREATED);
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
