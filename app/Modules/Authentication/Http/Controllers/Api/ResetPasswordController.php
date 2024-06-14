<?php

namespace App\Modules\Authentication\Http\Controllers\Api;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\ResetPasswordAction;
use App\Modules\Authentication\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Modules\User\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function __invoke(
        ResetPasswordRequest $request,
        ResetPasswordAction $action
    ) {
        try {
            $data = $request->validated();
            $user = $action->execute($data);
            $user['bearer_token'] = $user->createToken('bearer')->plainTextToken;
            return (new JsonResourceResponse(new UserResource($user), 200, 'Password reset success'))->response();
            
        } catch (\Exception | ValidationException $e) {
            if ($e instanceof ValidationException) {
                return (new JsonResourceResponse([], 422, json_encode($e->errors())))->response();
            }
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }
}
