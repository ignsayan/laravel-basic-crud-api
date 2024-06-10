<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::transaction(function () {

            for ($i = 0; $i < 10; $i++) {

                $user = User::create([
                    'uuid' => str()->uuid(),
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'phone_no' => fake()->phoneNumber(),
                    'password' => \Hash::make('12345678'),
                ]);
                $user->assignRole(User::ROLE_USER);
            }
        });
    }
}
