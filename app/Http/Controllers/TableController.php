<?php

namespace App\Http\Controllers;

use App\Traits\Messages;
use App\Service\TableService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\TableResource;
use App\Exceptions\ErrorPageException;
use App\Http\Requests\TableStoreRequest;
use App\Http\Requests\TableUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class TableController extends Controller
{
    use Messages;
    public function __construct(private readonly TableService $tableService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = $this->tableService->getAllTables();
        return TableResource::collection($tables);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TableStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->tableService->tableStore($request->validated());
            DB::commit();
            [$msg, $stc] = array($this->getSuccessMessage('Table'), Response::HTTP_CREATED);
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
    public function update(TableUpdateRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->tableService->tableUpdate($request->validated());
            DB::commit();
            [$msg, $stc] = array($this->getUpdateMessage('Table'), Response::HTTP_CREATED);
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
    public function destroy($table_id)
    {
        try{
            DB::beginTransaction();
            $this->tableService->deleteTable($table_id);
            DB::commit();
            [$msg, $stc] = array($this->getDestroyMessage('Table'), Response::HTTP_CREATED);
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
