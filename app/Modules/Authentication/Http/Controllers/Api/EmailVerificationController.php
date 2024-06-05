<?php

namespace App\Modules\Authentication\Http\Controllers\Api;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $user = User::where('uuid', $request->code)->firstOrFail();
            if ($user->hasVerifiedEmail()) {
                return (new JsonResourceResponse([], 400, 'User already verified.'))->response();
            }
            $user->markEmailAsVerified();
            return (new JsonResourceResponse([], 200, 'Email address verified successfully.'))->response();
            
        } catch (\Exception $e) {
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }

    public function resend(Request $request)
    {
        try {
            $user = User::where('uuid', $request->code)->firstOrFail();
            if ($user->hasVerifiedEmail()) {
                return (new JsonResourceResponse([], 200, 'User already verified.'))->response();
            }
            $user->sendEmailVerificationNotification();
            return (new JsonResourceResponse([], 200, 'Verification email sent successfully.'))->response();

        } catch (\Exception $e) {
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }

    public function isVerified(string $uuid)
    {
        try {
            $user = User::where('uuid', $uuid)->firstOrFail();
            if ($user->hasVerifiedEmail()) {
                return (new JsonResourceResponse(new UserResource($user), 200, 'Email already verified.'))->response();
            }
        return (new JsonResourceResponse(new UserResource($user), 200, 'Email is not verified.'))->response();
        
        } catch (\Exception $e) {
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }
}
