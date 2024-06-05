<?php

namespace App\Modules\Authentication\Http\Controllers\Api;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (\Auth::attempt($credentials)) {
                $user = \Auth::user();
                $user->tokens()->delete();
                $user['bearer_token'] = $user->createToken('bearer')->plainTextToken;
                return (new JsonResourceResponse(new UserResource($user), 200, 'Successfully logged in'))->response();
            } else {
                return (new JsonResourceResponse([], 401, 'Invalid credentials'))->response();
            }

        } catch (\Exception | ValidationException $e) {
            if ($e instanceof ValidationException) {
                return (new JsonResourceResponse([], 422, json_encode($e->errors())))->response();
            }
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }

    public function logout()
    {
        try {
            \Auth::user()->tokens()->delete();
            return (new JsonResourceResponse([], 200, 'Successfully logged out'))->response();

        } catch (\Exception $e) {
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }
}
