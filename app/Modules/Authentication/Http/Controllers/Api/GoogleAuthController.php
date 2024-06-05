<?php

namespace App\Modules\Authentication\Http\Controllers\Api;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\GoogleAuthAction;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function __invoke()
    {
        try {
            $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
            return (new JsonResourceResponse([], 200, $url))->response();

        } catch (\Exception $e) {
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }

    public function callback(GoogleAuthAction $action)
    {
        try {
            $OAuthUser = Socialite::driver('google')->stateless()->user();
            $response = $action->execute($OAuthUser);
            $user = $response->load('profile');
            $user['bearer_token'] = $user->createToken('bearer')->plainTextToken;
            return (new JsonResourceResponse(new UserResource($user), 200, 'Successfully authenticated'))->response();

        } catch (\Exception $e) {
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }
}
