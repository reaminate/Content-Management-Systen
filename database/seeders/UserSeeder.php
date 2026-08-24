<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'test',
            'email'=> 'test@gmail.com',
            'password'=> bcrypt('password'),
        ]);
        User::factory()->create([
            'name'=> 'admin',
            'email'=> 'admin@gmail.com',
            'is_admin' => true,
            'password'=> bcrypt('password'),
        ]);
        User::factory(5)->create();
    }
}
