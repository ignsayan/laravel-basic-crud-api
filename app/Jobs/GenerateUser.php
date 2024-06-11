<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \DB::transaction(function () {

            for ($i = 0; $i < 100; $i++) {

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
