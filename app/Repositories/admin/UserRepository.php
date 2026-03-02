<?php

namespace App\Repositories\Admin;

use App\Models\User;

class UserRepository
{
    public function getAllPaginated($perPage = 10)
    {
        return User::latest()->paginate($perPage);
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
