<?php

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('creates listing successfully with valid image', function () {
    Storage::fake('public');
    
    $creator = User::factory()->create(['role' => 'Creator']);
    $this->actingAs($creator);

    $file = UploadedFile::fake()->image('test.jpg', 800, 600)->size(1000);

    $response = $this->post(route('listings.store'), [
        'title' => 'Test Listing',
        'description' => 'A beautiful test listing',
        'price_per_day' => '100.00',
        'location' => 'Test City',
        'image' => $file,
        'is_available' => true,
    ]);

    $listing = Listing::latest()->first();
    $response->assertRedirect(route('listings.show', $listing));
    expect($listing->image_path)->not()->toBeNull();
    Storage::disk('public')->assertExists($listing->image_path);
});

it('creates listing successfully without image', function () {
    $creator = User::factory()->create(['role' => 'Creator']);
    $this->actingAs($creator);

    $response = $this->post(route('listings.store'), [
        'title' => 'Test Listing No Image',
        'description' => 'A test listing without image',
        'price_per_day' => '75.50',
        'location' => 'Test City',
        'is_available' => false,
    ]);

    $listing = Listing::latest()->first();
    $response->assertRedirect(route('listings.show', $listing));
    expect($listing->image_path)->toBeNull();
    expect($listing->is_available)->toBeFalse();
});

it('handles invalid image file gracefully', function () {
    Storage::fake('public');
    
    $creator = User::factory()->create(['role' => 'Creator']);
    $this->actingAs($creator);

    // Create a file that's too large
    $file = UploadedFile::fake()->image('large.jpg', 2000, 2000)->size(3000); // 3MB > 2MB limit

    $response = $this->post(route('listings.store'), [
        'title' => 'Test Listing',
        'description' => 'A test listing',
        'price_per_day' => '100.00',
        'location' => 'Test City',
        'image' => $file,
        'is_available' => true,
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['image']);
});

it('defaults is_available to true when not provided', function () {
    $creator = User::factory()->create(['role' => 'Creator']);
    $this->actingAs($creator);

    $response = $this->post(route('listings.store'), [
        'title' => 'Test Listing Default Available',
        'description' => 'A test listing with default availability',
        'price_per_day' => '125.00',
        'location' => 'Default City',
        // is_available not provided
    ]);

    $listing = Listing::latest()->first();
    $response->assertRedirect(route('listings.show', $listing));
    expect($listing->is_available)->toBeTrue();
});

it('handles is_available boolean properly', function () {
    $creator = User::factory()->create(['role' => 'Creator']);
    $this->actingAs($creator);

    // Test with false
    $response = $this->post(route('listings.store'), [
        'title' => 'Unavailable Listing',
        'description' => 'A test listing that is not available',
        'price_per_day' => '200.00',
        'location' => 'Unavailable City',
        'is_available' => false,
    ]);

    $listing = Listing::latest()->first();
    expect($listing->is_available)->toBeFalse();
    
    // Test with string '0'
    $response = $this->post(route('listings.store'), [
        'title' => 'String Zero Listing',
        'description' => 'A test listing with string zero',
        'price_per_day' => '150.00',
        'location' => 'String City',
        'is_available' => '0',
    ]);

    $listing = Listing::latest()->first();
    expect($listing->is_available)->toBeFalse();
});

it('updates listing with new image and deletes old one', function () {
    Storage::fake('public');
    
    $creator = User::factory()->create(['role' => 'Creator']);
    $this->actingAs($creator);

    // Create listing with initial image
    $initialFile = UploadedFile::fake()->image('initial.jpg')->size(500);
    $listing = Listing::factory()->create([
        'creator_id' => $creator->id,
        'image_path' => $initialFile->store('listings', 'public'),
    ]);
    Storage::disk('public')->put($listing->image_path, 'initial content');

    // Update with new image
    $newFile = UploadedFile::fake()->image('new.jpg')->size(600);
    
    $response = $this->put(route('listings.update', $listing), [
        'title' => 'Updated Listing',
        'description' => 'Updated description',
        'price_per_day' => '175.00',
        'location' => 'Updated City',
        'image' => $newFile,
        'is_available' => true,
    ]);

    $listing->refresh();
    $response->assertRedirect(route('listings.show', $listing));
    
    // New image should exist, old should not
    Storage::disk('public')->assertExists($listing->image_path);
    expect($listing->image_path)->not()->toContain('initial.jpg');
});