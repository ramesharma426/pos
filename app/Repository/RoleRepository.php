<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Role;
use App\Repository\Interfaces\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

}
