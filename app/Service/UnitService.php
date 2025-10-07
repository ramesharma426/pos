<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\UnitRepositoryInterface;

class UnitService
{
    public function __construct(private readonly UnitRepositoryInterface $unitRepository)
    {
    }

    public function getAllUnits()
    {
        return $this->unitRepository->getAllUnits();
    }

    /**
     * @throws ErrorPageException
     */
    public function unitStore(array|\Illuminate\Support\ValidatedInput $request): void
    {
        if($this->unitRepository->create(['name' => $request['name']]))
            return;

        throw new ErrorPageException('Cannot Create new Unit', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function unitUpdate(array|\Illuminate\Support\ValidatedInput $request): void
    {
        if($this->unitRepository->update($request['id'], ['name' => $request['name']]))
            return ;

        throw new ErrorPageException('Cannot Update Unit', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function deleteUnit($unit_id): void
    {
        if($this->unitRepository->destroy($unit_id))
            return ;

        throw new ErrorPageException('Cannot Remove Unit', 500);
    }
}
