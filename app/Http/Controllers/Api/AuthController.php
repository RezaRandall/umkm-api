<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user= User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'success'   => false,
                'message' => ['These credentials do not match our records.']
            ], 404);
        }
        
        $token = $user->createToken('ApiToken')->plainTextToken;
        
        $response = [
            'success'   => true,
            'user'      => $user,
            'token'     => $token
        ];
        
        return response($response, 201);
    }
    

    public function logout(Request $request)
    {
        // Delete all tokens for the authenticated user
        $request->user()->tokens()->delete();

        // Return success message as JSON
        return response()->json(['message' => 'Logged out']);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json(['user' => $user], 200);
    }
}