<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
             'name' => 'Admin User',
             'email' => 'admin@mail.com',
             'password' => '12345678',
             'user_type' => 'admin',
         ]);

         \App\Models\User::factory()->create([
             'name' => 'Regular User',
             'email' => 'user@mail.com',
             'password' => '12345678',
             'user_type' => 'user',
         ]);
    }
}
