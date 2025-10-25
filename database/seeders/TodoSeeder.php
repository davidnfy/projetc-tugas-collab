<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DailyTodo;
use App\Models\ImportantTodo;
use App\Models\UserTodo;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        // buat 3 user dummy
        $users = User::factory(3)->create();

        foreach ($users as $user) {
            DailyTodo::factory(5)->create(['user_id' => $user->id]);
            ImportantTodo::factory(3)->create(['user_id' => $user->id]);
            UserTodo::factory(4)->create(['user_id' => $user->id]);
        }
    }
}
