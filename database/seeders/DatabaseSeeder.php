<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Channel;
use App\Models\Message;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory(1, [
            'name' => 'Fin',
            'username' => 'fin',
            'email' => 'fin@gmail.com',
            'password' => 'fin12345',
        ])->create();

        User::factory(10)->create();
        Channel::factory(10)->create();
        Message::factory(100)->create();
    }
}
