<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Table;
use App\Repository\Interfaces\TableRepositoryInterface;

class TableRepository extends BaseRepository implements TableRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Table $model)
    {
        parent::__construct($model);
    }

    public function getAllTables()
    {
        return $this->model->select(['*'])->orderBy('created_at', 'desc')->get();
    }

}
