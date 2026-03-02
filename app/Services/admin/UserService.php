<?php

namespace App\Services\Admin;

use App\Repositories\Admin\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUsers()
    {
        return $this->repository->getAllPaginated();
    }

    public function getUser($id)
    {
        return $this->repository->findById($id);
    }

    public function createUser($request)
    {
        return $this->repository->create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'is_active' => $request->is_active ?? 0,
        ]);
    }

    public function updateUser($id, $request)
    {
        $user = $this->repository->findById($id);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->is_active ?? 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        return $this->repository->update($user, $data);
    }

    public function deleteUser($id)
    {
        $user = $this->repository->findById($id);

        return $this->repository->delete($user);
    }

    public function toggleStatus($id)
    {
        $user = $this->repository->findById($id);

        return $this->repository->update($user, [
            'is_active' => !$user->is_active
        ]);
    }
}
