<?php

namespace Database\Factories\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->userName();
        return [
            'name' => $name,
            'description' => fake()->sentence(5, true),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'facebook'=> fake()->url(),
            'linkedin'=> fake()->url(),
            'instagram'=> fake()->url(),
            'SEO_title' => $name,
            'SEO_description' => fake()->sentence(5, true),
        ];
    }
}
