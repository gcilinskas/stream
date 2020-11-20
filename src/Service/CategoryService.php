<?php

namespace App\Service;

use App\Entity\Category;
use Exception;

/**
 * Class CategoryService
 */
class CategoryService extends BaseService
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Category::class;
    }

    /**
     * @param Category $category
     *
     * @throws Exception
     */
    public function delete(Category $category)
    {
        parent::remove($category);
    }
}
