<?php

namespace Database\Factories\Models;

use App\Models\Item;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->name(),
            'menu_id' => $this->faker->randomElement(Menu::pluck('id')),
            'page_id' => fake()->optional(0.8)->randomElement(Page::pluck('id')->toArray()),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
