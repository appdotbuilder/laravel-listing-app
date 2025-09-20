<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ListingController;
use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Home page - shows available listings
Route::get('/', function () {
    $listings = Listing::with('creator')
        ->available()
        ->latest()
        ->take(6)
        ->get();
    
    return Inertia::render('welcome', [
        'listings' => $listings
    ]);
})->name('home');

// Public listings (no auth required)
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();
        $stats = [];
        
        if ($user->isSuperAdmin()) {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_listings' => \App\Models\Listing::count(),
                'total_bookings' => \App\Models\Booking::count(),
            ];
        } elseif ($user->isCreator()) {
            $stats = [
                'my_listings' => $user->listings()->count(),
                'total_bookings' => \App\Models\Booking::whereHas('listing', fn($q) => $q->where('creator_id', $user->id))->count(),
            ];
        } else {
            $stats = [
                'my_bookings' => $user->bookings()->count(),
                'upcoming_bookings' => $user->bookings()->where('start_date', '>', now())->count(),
            ];
        }
        
        return Inertia::render('dashboard', [
            'stats' => $stats
        ]);
    })->name('dashboard');
    
    // Listings (authenticated routes)
    Route::resource('listings', ListingController::class)->except(['index', 'show']);
    
    // Bookings
    Route::resource('bookings', BookingController::class);
    
    // Admin routes (SuperAdmin only)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/{resource}', [AdminController::class, 'show'])->name('show');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
