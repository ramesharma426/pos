<?php

namespace App\Http\Controllers;

use App\Traits\Messages;
use App\Service\CategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ErrorPageException;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategorySearchRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    use Messages;
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getAllCategory();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->categoryService->categoryStore($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getSuccessMessage('Category'), Response::HTTP_CREATED);
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
    public function update(CategoryUpdateRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->categoryService->categoryUpdate($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getUpdateMessage('Category'), Response::HTTP_CREATED);
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
    public function destroy($category_id)
    {
        try{
            DB::beginTransaction();
            $this->categoryService->deleteCategory($category_id);
            DB::commit();
            [$msg, $stc] = array($this->getDestroyMessage('Category'), Response::HTTP_CREATED);
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
