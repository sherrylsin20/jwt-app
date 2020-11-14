<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'username' => 'required|min:6',
            'password' => 'required|min:6'
        ]);

        $attributes = [
            'email' => $request->email,
            'username' => $request->username,
            'password' => app('hash')->make($request->password)
        ];

        $user = User::create($attributes);
        return $user;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = request(['username', 'password']);
        if(!$token = auth()->attempt($credentials)) {
            return response()->json([
                'msg' => 'login gagal'
            ], 401);
        } else {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 401);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'msg' => 'Logout berhasil'
        ], 200);
    }

    public function me()
    {
        return response()->json([
            'data' => auth()->user()
        ], 200);
    }

    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
}
