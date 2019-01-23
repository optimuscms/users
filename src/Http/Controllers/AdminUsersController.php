<?php

namespace Optimus\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Optimus\Users\Models\AdminUser;
use Optimus\Users\Http\Resources\AdminUserResource;

class AdminUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

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

        // if ($request->filled('avatar_id')) {
        //     $user->attachMedia($request->input('avatar_id'), 'avatar');
        // }

        return new AdminUserResource($user);
    }

    public function show(Request $request, $id = null)
    {
        $user = $id
            ? AdminUser::findOrFail($id)
            : $request->user('admin');

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
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        // $user->clearMediaGroup('avatar');

        // if ($request->filled('avatar_id')) {
        //     $user->attachMedia($request->input('avatar_id'), 'avatar');
        // }

        return new AdminUserResource($user);
    }

    public function destroy($id)
    {
        AdminUser::findOrFail($id)->delete();

        return response(null, 204);
    }

    protected function validateUser(Request $request, AdminUser $user = null)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'username' => [
                'required',
                Rule::unique('admin_users')->ignore($user)
            ],
            'password' => ($user ? 'nullable' : 'required') . '|min:6',
            // 'avatar_id' => 'nullable|exists:media,id'
        ]);
    }
}
