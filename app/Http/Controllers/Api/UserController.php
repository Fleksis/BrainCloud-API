<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::paginate(10));
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'image' => '',
            'name' => '',
            'email' => 'email',
            'password' => '',
        ]);
        if($request->hasFile('image'))
        {
            Storage::disk('local')->delete('public/userAvatars/'.$user->image);
            $image = $validated['image'];
            $validated['image'] = $image->hashName();
            $image->store('public/userAvatars');
        }
        if (!isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        // TODO
        $user->delete();
        Storage::disk('local')->delete('public/userAvatars/'.$user->image);
        return new UserResource($user);
    }

    public function getFile (Request $request, User $user)
    {
        if(!$request->hasValidSignature()) return abort(401);
        $user->image = Storage::disk('local')->path('public/userAvatars/' .$user->image);
        return response()->file($user->image);
    }

    public function userFilter (Request $request)
    {
        $validated = $request->validate([
           'name' => 'required'
        ]);

        $users = User::where('name', 'LIKE', "%{$validated['name']}%")->paginate(10);
        return UserResource::collection($users);
    }
}
