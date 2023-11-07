<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{

    public function login(AuthRequest $request): Response
    {
        $credentials = $request->only(['username', 'password']);
        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'password' => 'The provided credentials are incorrect.'
            ]);
        }
        /**
         * @var \App\Models\User
         */
        $user = auth()->user();
        $token = Cache::get('user_token_' . $user->id);
        if (!$token) {
            $user->tokens()->where('name', $request->device_name)->first()?->delete();
            $token = $user->createToken($request->device_name)->plainTextToken;
            Cache::put('user_token_' . $user->id, $token, 60 * 60 * 24 * 7);
        }

        return response([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function me(): Response
    {
        /** @var User */
        $user = auth()->user();
        $user->groups;
        return response($user);
    }

    public function changePassword(PasswordRequest $request): Response
    {
        /** @var User */
        $user = auth()->user();
        $user->update(['password' => $request->password]);
        $user->groups;
        return response($user, 201);
    }

    public function update(ProfileRequest $request): Response
    {
        /** @var User */
        $user = auth()->user();
        $update = [
            'name' => $request->name,
            'identity' => $request->identity,
            'role' => $request->role,
            'phone' => $request->phone,
            'email' => $request->email,
        ];
        $user->update($update);
        $user->groups;
        return response($user, 200);
    }
}
