<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\TableRepositoryInterface;

class TableService
{
    public function __construct(private readonly TableRepositoryInterface $tableRepository)
    {
    }

    public function getAllTables()
    {
        return $this->tableRepository->getAllTables();
    }

    /**
     * @throws ErrorPageException
     */
    public function tableStore(array $request): void
    {
        $data = [
            'number' => $request['number'],
            'capacity' => $request['capacity']
        ];

        if($this->tableRepository->create($data))
            return;

        throw new ErrorPageException('Cannot Create new Table', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function tableUpdate(array $request): void
    {
        $data = [
            'number' => $request['number'],
            'capacity' => $request['capacity']
        ];

        if($this->tableRepository->update($request['id'],$data))
            return;

        throw new ErrorPageException('Cannot Update Table');
    }

    /**
     * @throws ErrorPageException
     */
    public function deleteTable($table_id): void
    {
        if($this->tableRepository->destroy($table_id))
            return ;

        throw new ErrorPageException('Cannot Remove Table', 500);
    }

}
