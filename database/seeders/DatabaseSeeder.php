<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create SuperAdmin
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@bookspace.com',
            'password' => Hash::make('password'),
            'role' => 'SuperAdmin',
        ]);

        // Create some Creators
        $creators = User::factory()->count(3)->create([
            'role' => 'Creator',
        ]);

        // Create some EndUsers
        $endUsers = User::factory()->count(5)->create([
            'role' => 'EndUser',
        ]);

        // Create listings for each creator
        foreach ($creators as $creator) {
            $listings = Listing::factory()->count(random_int(2, 4))->create([
                'creator_id' => $creator->id,
            ]);

            // Create some bookings for these listings
            foreach ($listings as $listing) {
                if (random_int(0, 1)) { // 50% chance of having bookings
                    Booking::factory()->count(random_int(1, 3))->create([
                        'listing_id' => $listing->id,
                        'user_id' => $endUsers->random()->id,
                    ]);
                }
            }
        }

        // Create a test user for easy login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'EndUser',
        ]);

        User::factory()->create([
            'name' => 'Test Creator',
            'email' => 'creator@example.com',
            'password' => Hash::make('password'),
            'role' => 'Creator',
        ]);
    }
}
