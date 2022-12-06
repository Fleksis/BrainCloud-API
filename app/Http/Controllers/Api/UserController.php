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
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UserRequest $request, User $user)
    {
        Storage::disk('local')->delete('public/userAvatars/'.$user->image);
        $validated = $request->validated();
        if($request->hasFile('image'))
        {
            $image = $validated['image'];
            $validated['image'] = $image->hashName();
            $image->store('public/userAvatars');
        }
        $validated['password'] = Hash::make($validated['password']);
        $user->update($validated);
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        Storage::disk('local')->delete('public/userAvatars/'.$user->image);
        $user->delete();
        return new UserResource($user);
    }

    public function getFile (Request $request, User $user)
    {
        if(!$request->hasValidSignature()) return abort(401);
        $user->image = Storage::disk('local')->path('public/userAvatars/' .$user->image);
        return response()->file($user->image);
    }
}
