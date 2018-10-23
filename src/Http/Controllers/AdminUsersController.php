<?php

namespace Optimus\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Optimus\Users\Models\AdminUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
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
        $this->validate($request);

        $user = new AdminUser();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));

        $user->save();

        return new AdminUserResource($user);
    }

    public function show($id = null)
    {
        $user = ! is_null($id)
            ? AdminUser::findOrFail($id)
            : Auth::guard('admin')->user();

        return new AdminUserResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = AdminUser::findOrFail($id);

        $this->validate($request, $user);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return new AdminUserResource($user);
    }

    public function destroy($id)
    {
        AdminUser::findOrFail($id)->delete();

        return response(null, 204);
    }

    protected function validate(Request $request, AdminUser $user = null)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'username' => [
                'required', 'string',
                Rule::unique('admin_users')
                    ->where(function (Builder $query) use ($user) {
                        $query->when($user, function (Builder $query) use ($user) {
                            $query->where('id', '<>', $user->id);
                        });
                    })
            ],
            'password' => ($user ? 'nullable' : 'required') . '|string|min:6',
        ]);
    }
}
