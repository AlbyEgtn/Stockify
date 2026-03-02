<?php

namespace App\Repositories\Admin;

use App\Models\Supplier;

class SupplierRepository
{
    public function getAllPaginated($perPage = 10)
    {
        return Supplier::latest()->paginate($perPage);
    }

    public function findById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function update($supplier, array $data)
    {
        return $supplier->update($data);
    }

    public function delete($supplier)
    {
        return $supplier->delete();
    }
}
