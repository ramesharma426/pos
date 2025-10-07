<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\CategoryRepositoryInterface;

class CategoryService
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function getAllCategory()
    {
        return $this->categoryRepository->getAllCategory();
    }

    /**
     * @throws ErrorPageException
     */
    public function categoryStore(array|\Illuminate\Support\ValidatedInput $request): void
    {
        if($this->categoryRepository->create(['name' => $request['name']]))
            return;

        throw new ErrorPageException('Cannot Create new Category', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function categoryUpdate(\Illuminate\Support\ValidatedInput|array $request): void
    {
        if($this->categoryRepository->update($request['id'],['name' => $request['name']]))
            return ;

        throw new ErrorPageException('Cannot Update Category', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function deleteCategory($category_id): void
    {
        if($this->categoryRepository->destroy($category_id))
            return ;

        throw new ErrorPageException('Cannot Remove Category', 500);
    }


}
