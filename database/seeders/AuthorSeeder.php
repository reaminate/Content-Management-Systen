<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;
class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::factory()->create([
            'name' => 'Amin',
            'email' => 'amin@gmail.com',
            'user_id' => 1,
        ]);
        Author::factory()->create([
            'name' => 'zaa',
            'email' => 'zaa@gmail.com',
            'user_id' => 3,
        ]);
        Author::factory()->count(9)->create();
    }
}
