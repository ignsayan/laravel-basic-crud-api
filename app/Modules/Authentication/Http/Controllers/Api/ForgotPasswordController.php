<?php

namespace App\Modules\Authentication\Http\Controllers\Api;

use App\Extends\JsonResourceResponse;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function __invoke(ForgotPasswordRequest $request)
    {
        try {
            $response = \DB::transaction(function () use ($request) {

                $token = $this->generatePasswordResetLinkWithToken();
                $response = $this->broker()->sendResetLink(
                    $this->credentials($request)
                );
                $this->savePasswordResetToken($request->validated('email'), $token);
                return $response;
            });
            return $response == \Password::RESET_LINK_SENT
                ? (new JsonResourceResponse([], 200, trans($response)))->response()
                : (new JsonResourceResponse([], 422, trans($response)))->response();

        } catch (\Exception | ValidationException $e) {
            if ($e instanceof ValidationException) {
                return (new JsonResourceResponse([], 422, json_encode($e->errors())))->response();
            }
            return (new JsonResourceResponse([], 500, $e->getMessage()))->response();
        }
    }

    protected function generatePasswordResetLinkWithToken()
    {
        $token = \Str::random(60);
        ResetPassword::createUrlUsing(function (User $user) use ($token) {
            return config('settings.frontend_url')
                . '/reset-password?token=' . $token
                . '&email=' . $user->email;
        });
        return $token;
    }

    protected function savePasswordResetToken(string $email, string $token)
    {
        return \DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()
        ]);
    }
}
