<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $validated = $request->validated();
        $image = $validated['image'];
        $validated['image'] = $image->hashName();
        $image->store('public/userAvatars');
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        return new UserResource($user);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validated))
        {
            return response()->json([
                'data' => 'Incorrect Data.'
            ], 403);
        }

        $token = auth()->user()->createToken('accessToken')->accessToken;
        return response()->json([
            'user' => new UserResource(auth()->user()),
            'access_token' => $token,
        ]);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json([
            'message' => [
                'type' => 'success',
                'data' => 'Succesfully log out.'
            ]
        ]);
    }

    public function user()
    {
        return new UserResource(auth()->user());
    }
}
