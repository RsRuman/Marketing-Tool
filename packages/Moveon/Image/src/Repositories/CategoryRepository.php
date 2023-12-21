<?php

namespace Moveon\Image\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Moveon\Image\Models\Category;

class CategoryRepository
{
    public function all(): Collection|array
    {
        return Category::query()
            ->orderBy('name', "ASC")
            ->get();
    }
}
