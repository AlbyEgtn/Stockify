<?php

namespace App\Repositories\Admin;

use App\Models\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::latest()->get();
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($category, array $data)
    {
        return $category->update($data);
    }

    public function delete($category)
    {
        return $category->delete();
    }
}
