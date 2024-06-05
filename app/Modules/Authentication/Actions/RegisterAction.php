<?php

namespace App\Modules\Authentication\Actions;

use App\Models\User;
// use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterAction
{
    public function execute(array $data): User
    {
        $data['uuid'] = \Str::uuid();
        $data['password'] = \Hash::make($data['password']);
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        $user = \DB::transaction(function () use ($data) {

            $user = User::create($data);
            $user->assignRole(User::ROLE_USER);
            return $user;
        });

        $user->sendEmailVerificationNotification();
        return $user;
    }
}
