<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register(UserRequest $request) {
        $validated = $request->validated();
        $image = $validated['image'];
        $validated['image'] = $image->hashName();
        $image->store('public/items');
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        return new UserResource($user);
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'data' => 'Incorrect Data.'
            ]);
        }

        $token = auth()->user()->createToken('accessToken')->accessToken;
        return response()->json([
            'user' => new UserResource(auth()->user()),
            'access_token' => $token,
        ]);
    }

    public function logout() {
        auth()->user()->token()->revoke();

        return response()->json([
            'message' => [
                'type' => 'success',
                'data' => 'Succesfully log out.'
            ]
        ]);
    }

    public function user() {
        return response()->json(new UserResource(auth()->user()));
    }

    public function getFile (Request $request, User $user) {
        if(!$request->hasValidSignature()) return abort(401);
        $user->image = Storage::disk('local')->path('public/items/' .$user->image);
        return response()->file($user->image);
    }
}
