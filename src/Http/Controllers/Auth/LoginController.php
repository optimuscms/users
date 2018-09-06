<?php

namespace Optimus\Users\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Optimus\Users\Http\Resources\AdminUser as AdminUserResource;
use Optimus\Users\Models\AdminUser;

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

        return $this->authenticated($token, $this->guard()->user());
    }

    protected function authenticated($token, $user)
    {
        return response()->json([
            'user' => new AdminUserResource($user /* ->load('permissions') */),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function username()
    {
        return 'username';
    }

    public function refresh()
    {
        $token = $this->guard()->refresh();
        $user = $this->guard()->setToken($token)->user();

        return $this->authenticated($token, $user);
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
