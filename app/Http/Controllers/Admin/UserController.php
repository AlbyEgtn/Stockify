<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->service->getAllUsers();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $this->service->createUser($request);
        logActivity(
            'create_user',
            'Menambahkan user: ' . $request->email
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->service->getUser($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|string',
        ]);
        $user = $this->service->getUser($id);
        $this->service->updateUser($id, $request);
        logActivity(
            'update_user',
            'Mengupdate user: ' . $user->email
        );
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = $this->service->getUser($id);
        $email = $user->email;

        $this->service->deleteUser($id);

        logActivity(
            'delete_user',
            'Menghapus user: ' . $email
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }


    public function toggleStatus($id)
    {
        $this->service->toggleStatus($id);

        return back()->with('success', 'Status user diperbarui.');
    }
}
