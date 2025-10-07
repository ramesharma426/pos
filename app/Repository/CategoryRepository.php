<?php

namespace App\Repository;

use App\Exceptions\ErrorPageException;
use App\Models\Category;
use App\Repository\Interfaces\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * @throws ErrorPageException
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getAllCategory(){
        return $this->model
            ->orderBy('created_at', 'desc')->get();
    }
}
