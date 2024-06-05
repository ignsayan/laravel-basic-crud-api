<?php

namespace App\Modules\Authentication\Actions;

use App\Models\User;
use Laravel\Socialite\Contracts\User as OAuthUser;

class GoogleAuthAction
{
    public function execute(OAuthUser $OAuthUser): User
    {
        return \DB::transaction(function () use ($OAuthUser) {

            $user = User::firstOrCreate(
                ['email' => $OAuthUser->email],
                [
                    'uuid' => \Str::uuid(),
                    'oauth_id' => $OAuthUser->id,
                    'name' => $OAuthUser->name,
                    'email' => $OAuthUser->email,
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole(User::ROLE_USER);
            return $user;
        });
    }
}
