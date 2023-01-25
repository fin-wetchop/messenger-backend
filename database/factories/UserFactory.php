<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '1234567890',
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (User $user) {
            $user['password'] = Hash::make($user['password']);
        });
    }
}
