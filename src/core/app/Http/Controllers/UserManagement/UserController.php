<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\RobotController;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Providers\JWTAuthServiceProvider;
use Validator;

class UserController extends RobotController
{
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['success' => 0, 'error' => trans('auth.failed')], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['success' => 0, 'error' => $e->getMessage()], 500);
        }
        $token = compact('token');
        $token=$token['token'];

        $user = JWTAuth::authenticate($token);
        $user = $user->toArray();

        // if no errors are encountered we can return a JWT
        return response()->json([
            'success' => 1,
            'response' => ['token' => $token,'user' => $user],
        ]);
    }

    public function logout() {
        return response()->json([
            'success' => 1,
            'response' => 'Robot API v1.0'
        ]);
    }
}
