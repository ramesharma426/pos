<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\RoleRepositoryInterface;

class RoleService
{
    public function __construct(private readonly RoleRepositoryInterface $roleRepository)
    {
    }

    /**
     * @throws ErrorPageException
     */
    public function getAllRoles(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->roleRepository->all();
    }

}
