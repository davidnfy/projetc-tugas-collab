<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // sesuaikan dengan kolom di tabel users milikmu
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            // default password 'password' (ter-hash)
            'password' => Hash::make('password'),
        ];
    }
}
