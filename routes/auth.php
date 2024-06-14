<?php

use App\Modules\User\Http\Resources\UserResource;
use App\Modules\Authentication\Http\Controllers\Api\AuthenticationController;
use App\Modules\Authentication\Http\Controllers\Api\EmailVerificationController;
use App\Modules\Authentication\Http\Controllers\Api\ForgotPasswordController;
use App\Modules\Authentication\Http\Controllers\Api\GoogleAuthController;
use App\Modules\Authentication\Http\Controllers\Api\RegistrationController;
use App\Modules\Authentication\Http\Controllers\Api\ResetPasswordController;
use App\Modules\Authentication\Http\Middleware\IsVerifiedEmail;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->prefix('api')
    ->group(function () {

        Route::post('/register', RegistrationController::class);
        Route::post('/login', [AuthenticationController::class, 'login']);

        Route::get('/google/auth', GoogleAuthController::class);
        Route::get('/google/callback', [GoogleAuthController::class, 'callback']);

        Route::post('/forgot-password/email', ForgotPasswordController::class);
        Route::post('/reset-password', ResetPasswordController::class);

        Route::get('/verification/email', EmailVerificationController::class);
        Route::get('/verification/check/{uuid}', [EmailVerificationController::class, 'isVerified']);
        Route::get('/verification/resend', [EmailVerificationController::class, 'resend']);


        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthenticationController::class, 'logout']);

            Route::middleware(IsVerifiedEmail::class)->group(function () {
            });
        });
    });
