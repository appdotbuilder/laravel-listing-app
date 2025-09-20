<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 day', '+30 days');
        $endDate = fake()->dateTimeBetween($startDate, '+35 days');
        $days = $startDate->diff($endDate)->days + 1;
        
        return [
            'listing_id' => Listing::factory(),
            'user_id' => User::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_amount' => fake()->randomFloat(2, $days * 50, $days * 500),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'payment_gateway' => fake()->randomElement(['stripe', 'razorpay']),
            'payment_id' => fake()->uuid(),
            'status' => fake()->randomElement(['confirmed', 'cancelled', 'completed']),
        ];
    }

    /**
     * Indicate that the booking is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);
    }

    /**
     * Indicate that the booking is pending payment.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'pending',
            'status' => 'confirmed',
        ]);
    }
}