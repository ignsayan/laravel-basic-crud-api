<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'uuid' => \Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'superadmin@yopmail.com',
            'phone_no' => '9876543210',
            'password' => \Hash::make('12345678'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        $user->assignRole(User::ROLE_ADMIN);
    }
}
