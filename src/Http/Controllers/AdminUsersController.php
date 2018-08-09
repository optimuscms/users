<?php

namespace Optimus\Users\Http\Controllers;

use Illuminate\Http\Request;
use Optimus\Users\AdminUser;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Optimus\Users\Http\Resources\AdminUser as AdminUserResource;

class AdminUsersController extends Controller
{
    public function index()
    {
        $users = AdminUser::all();

        return AdminUserResource::collection($users);
    }

    public function store(Request $request)
    {
        $this->validateUser($request);

        $user = AdminUser::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password'))
        ]);

        $user->givePermissionTo($request->input('permissions'));

        return new AdminUserResource($user);
    }

    public function show($id)
    {
        $user = AdminUser::findOrFail($id);

        return new AdminUserResource($user);
    }

    public function me()
    {
        $user = Auth::guard('admin')->user();

        return new AdminUserResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = AdminUser::findOrFail($id);

        $this->validateUser($request, $user);

        $user->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username')
        ]);

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        $user->syncPermissions($request->input('permissions'));

        $user->save();

        return response(null, 204);
    }

    public function destroy($id)
    {
        AdminUser::findOrFail($id)->delete();

        return response(null, 204);
    }

    protected function validateUser(Request $request, AdminUser $user = null)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'username' => [
                'required', 'string',
                Rule::unique('admin_users')->where(function ($query) use ($user) {
                    $query->when($user, function ($query) use ($user) {
                        $query->where('id', '<>', $user->id);
                    });
                })
            ],
            'password' => ($user ? 'nullable' : 'required') . '|string|min:6',
            'permissions' => 'array|required',
            'permissions.*' => 'exists:permissions,id'
        ]);
    }
}
