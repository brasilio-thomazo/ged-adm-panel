<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
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

    public function login2(AuthRequest $request): Response
    {
        if (!auth('web')->attempt($request->except('remember'))) {
            throw ValidationException::withMessages(['password' => 'password incorrect']);
        }
        /**
         * @var User
         */
        $user = auth('web')->user();
        $user->groups;

        return response($user);
    }

    public function me2(): Response
    {
        /**
         * @var User
         */
        $user = auth('web')->user();
        $user->groups;
        return response($user);
    }

    public function logout()
    {
        auth("web")->logout();
        return response([], 204);
    }
}
