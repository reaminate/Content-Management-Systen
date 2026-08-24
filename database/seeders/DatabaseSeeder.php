<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ImageSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            AuthorSeeder::class,     
            TagSeeder::class,
            BlogSeeder::class,
            Blog_tagSeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            ItemSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
