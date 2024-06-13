<?php

namespace App\Modules\User\Http\Controllers\Api;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Modules\User\Http\Requests\MediaRequest;
use Illuminate\Validation\ValidationException;

class MediaController extends Controller
{
    public function __invoke(string $uuid, MediaRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::where('uuid', $uuid)->firstOrFail();

            if (!is_null($user->getFirstMedia($data['key']))) {
                $user->deleteMedia($user->getFirstMedia($data['key'])->id);
            }
            $user->addMedia($data['file'])->toMediaCollection($data['key']);

            $user[$data['key']] = $user->refresh()->getFirstMediaUrl($data['key']);
            return (new JsonResourceResponse(
                new UserResource($user),
                200,
                str()->ucfirst($data['key']) . ' updated successfully'
            )
            )->response();

        } catch (\Exception | ValidationException $e) {

            if ($e instanceof ValidationException) {
                return (new JsonResourceResponse([], 422, json_encode($e->errors())))->response();
            }
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }
}
