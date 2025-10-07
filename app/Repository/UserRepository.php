<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\User;
use App\Repository\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAllUsers($paginator, $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        //return $this->model->select(['*'])->orderBy('created_at', 'desc')->get();
        return $this->model
            ->with('role:id,name')
            ->when(array_key_exists("search_term", $request), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->orWhere('name', 'LIKE', "%{$request['search_term']}%")
                        ->orWhere('email', 'LIKE', "%{$request['search_term']}%");
                });
            })->orderBy('created_at', 'desc')->paginate($paginator);
    }
}
