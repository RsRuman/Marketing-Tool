<?php

namespace Moveon\Image\Services;

use Moveon\Image\Repositories\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get categories
     * @return mixed
     */
    public function getCategories(): mixed
    {
        return $this->categoryRepository->all();
    }
}
