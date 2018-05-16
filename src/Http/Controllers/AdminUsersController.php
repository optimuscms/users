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

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password'))
        ]);

        return response(null, 204);
    }

    public function destroy($id)
    {
        AdminUser::findOrFail($id)->delete();

        return response(null, 204);
    }

    protected function validateUser(Request $request, $user = null)
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
            'password' => 'required|string|min:6'
        ]);
    }
}
