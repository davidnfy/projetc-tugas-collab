<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class DailyTodoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // otomatis bikin user kalau belum ada
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(6),
            'is_completed' => $this->faker->boolean(30), // 30% kemungkinan true
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+5 days'),
        ];
    }
}
