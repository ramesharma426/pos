<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Unit;
use App\Repository\Interfaces\UnitRepositoryInterface;

class UnitRepository extends BaseRepository implements UnitRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Unit $model)
    {
        parent::__construct($model);
    }

    public function getAllUnits()
    {
        return $this->model
            ->orderBy('created_at', 'desc')->get();
    }
}
