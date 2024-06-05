<?php

namespace App\Modules\Authentication\Actions;

use App\Models\User;

class ResetPasswordAction
{
    public function execute(array $data): User
    {
        return \DB::transaction(function () use ($data) {

            $user = User::firstWhere('email', $data['email']);
            $user->update([
                'password' => \Hash::make($data['password'])
            ]);
            $passwordResetTableData = \DB::table('password_resets')->where('email', $user->email);
            $passwordResetTableData->delete();
            return $user;
        });
    }
}
