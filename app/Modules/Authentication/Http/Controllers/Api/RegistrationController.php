<?php

namespace App\Modules\Authentication\Http\Controllers\Api;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Actions\RegisterAction;
use App\Modules\Authentication\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Modules\User\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    public function __invoke(
        RegisterRequest $request,
        RegisterAction $action
    ) {
        try {
            $data = $request->validated();
            $user = $action->execute($data);
            return (new JsonResourceResponse(new UserResource($user), 200, 'User data stored'))->response();
            
        } catch (\Exception | ValidationException $e) {
            if ($e instanceof ValidationException) {
                return (new JsonResourceResponse([], 422, json_encode($e->errors())))->response();
            }
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }
}
