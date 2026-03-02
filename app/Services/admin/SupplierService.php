<?php

namespace App\Services\Admin;

use App\Repositories\Admin\SupplierRepository;

class SupplierService
{
    protected SupplierRepository $repository;

    public function __construct(SupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllSuppliers()
    {
        return $this->repository->getAllPaginated();
    }

    public function getSupplierById($id)
    {
        return $this->repository->findById($id);
    }

    public function createSupplier($request)
    {
        return $this->repository->create([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);
    }


    public function updateSupplier($id, $request)
    {
        $supplier = $this->repository->findById($id);

        return $this->repository->update($supplier, [
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
    }

    public function deleteSupplier($id)
    {
        $supplier = $this->repository->findById($id);

        return $this->repository->delete($supplier);
    }
}
