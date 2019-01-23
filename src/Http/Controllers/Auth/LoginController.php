<?php

namespace Optimus\Users\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        return route('admin.dashboard');
    }

    public function showLoginForm()
    {
        return view('admin::auth.login');
    }

    public function username()
    {
        return 'username';
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }
}
