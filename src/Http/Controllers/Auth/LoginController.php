<?php

namespace Optimus\Users\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($token = $this->guard()->attempt($this->credentials($request))) {
            return $this->sendLoginResponse($request, $token);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function sendLoginResponse(Request $request, $token)
    {
        $this->clearLoginAttempts($request);

        return $this->tokenResponse($token);
    }

    public function username()
    {
        return 'username';
    }

    public function refresh()
    {
        $token = $this->guard()->refresh();

        return $this->tokenResponse($token);
    }

    protected function tokenResponse($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response(null, 204);
    }

    public function guard()
    {
        return Auth::guard('admin');
    }
}
