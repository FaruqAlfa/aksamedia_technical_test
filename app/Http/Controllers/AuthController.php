<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use function Laravel\Prompts\error;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        //check if user exists
        $user = User::where('username', $request->username)->first();

        //check password eith hash
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'status' => error('Username atau password tidak ditemukan'),
                'message' => 'Login gagal'
            ], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil',
            'data' => [
                    'token' => $token,

                'admin' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'email' => $user->email,
                ]
            ]

        ]);
    }

}
