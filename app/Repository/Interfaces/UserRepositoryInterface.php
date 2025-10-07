<?php

namespace App\Repository\Interfaces;

interface UserRepositoryInterface
{
    public function getAllUsers($paginator, $request);
}
