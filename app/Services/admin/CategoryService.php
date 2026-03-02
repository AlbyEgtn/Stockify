<?php

namespace App\Services\Admin;

use App\Repositories\Admin\CategoryRepository;

class CategoryService
{
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCategories()
    {
        return $this->repository->getAll();
    }

    public function getCategoryById($id)
    {
        return $this->repository->findById($id);
    }

    public function createCategory($request)
    {
        return $this->repository->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }

    public function updateCategory($id, $request)
    {
        $category = $this->repository->findById($id);

        return $this->repository->update($category, [
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }

    public function deleteCategory($id)
    {
        $category = $this->repository->findById($id);

        return $this->repository->delete($category);
    }
}
