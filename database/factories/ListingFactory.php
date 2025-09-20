<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(3),
            'price_per_day' => fake()->randomFloat(2, 50, 500),
            'location' => fake()->city() . ', ' . fake()->randomElement(['CA', 'NY', 'FL', 'TX', 'WA']),
            'image_path' => null,
            'is_available' => fake()->boolean(80),
            'creator_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the listing is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }

    /**
     * Indicate that the listing is premium priced.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'price_per_day' => fake()->randomFloat(2, 500, 2000),
            'title' => 'Luxury ' . fake()->sentence(2),
        ]);
    }
}