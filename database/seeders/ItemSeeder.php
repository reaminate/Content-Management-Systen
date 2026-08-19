<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::all()->each(function (Menu $menu) {
            Item::factory()
                ->count(4)
                ->sequence(fn ($sequence) => ['order' => $sequence->index])
                ->create(['menu_id' => $menu->id]);
        });
    }
}
