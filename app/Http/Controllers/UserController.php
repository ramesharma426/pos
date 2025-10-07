<?php

namespace App\Http\Controllers;

use App\Traits\Messages;
use App\Service\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use App\Exceptions\ErrorPageException;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Requests\UserNameUpdateRequest;
use App\Http\Requests\UserRoleUpdateRequest;
use App\Http\Requests\UserEmailUpdateRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UserPasswordUpdateRequest;

class UserController extends Controller
{
    use Messages;

    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * @throws ErrorPageException
     */
    public function index(UserSearchRequest $request)
    {
        $users = $this->userService->getAllUsers(15, $request->validated())->withQueryString();
        return UserResource::collection($users);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->userService->userStore($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getSuccessMessage('User'), Response::HTTP_CREATED);
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

    public function nameUpdate(UserNameUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->userService->nameUpdateUser($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getMessage('Name Changed'), Response::HTTP_CREATED);
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

    public function emailUpdate(UserEmailUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->userService->emailUpdateEmail($request->passedValidation());
            DB::commit();
            [$msg, $stc] = array($this->getMessage('Email Changed'), Response::HTTP_CREATED);
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

    public function passwordUpdate(UserPasswordUpdateRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->userService->passwordUpdateUser($request->validated());
            DB::commit();
            [$msg, $stc] = array($this->getMessage('Password Updated'), Response::HTTP_CREATED);
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

    public function roleUpdate(UserRoleUpdateRequest $request)
    {
        try{
            DB::beginTransaction();
            $this->userService->roleUpdateUser($request->validated());
            DB::commit();
            [$msg, $stc] = array($this->getMessage('Role Changed'), Response::HTTP_CREATED);
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

    public function destroy($user_id)
    {
        try{
            DB::beginTransaction();
            $this->userService->deleteUser($user_id);
            DB::commit();
            [$msg, $stc] = array($this->getDestroyMessage('User'), Response::HTTP_CREATED);
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

}
