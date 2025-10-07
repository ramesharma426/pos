<?php

namespace App\Http\Controllers;

use App\Traits\Messages;
use App\Service\UnitService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UnitResource;
use App\Exceptions\ErrorPageException;
use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitSearchRequest;
use App\Http\Requests\UnitUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class UnitController extends Controller
{
    use Messages;
    /**
     * Display a listing of the resource.
     */

    public function __construct(private UnitService $unitService)
    {
    }

    public function index()
    {
        $units = $this->unitService->getAllUnits();
        return UnitResource::collection($units);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->unitService->unitStore($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getSuccessMessage('Unit'), Response::HTTP_CREATED);
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
    public function update(UnitUpdateRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->unitService->unitUpdate($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getUpdateMessage('Unit'), Response::HTTP_CREATED);
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
    public function destroy($unit_id)
    {
        try{
            DB::beginTransaction();
            $this->unitService->deleteUnit($unit_id);
            DB::commit();
            [$msg, $stc] = array($this->getDestroyMessage('Unit'), Response::HTTP_CREATED);
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
