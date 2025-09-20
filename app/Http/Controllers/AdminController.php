<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Ensure only SuperAdmin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $stats = [
            'total_users' => User::count(),
            'total_listings' => Listing::count(),
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::where('payment_status', 'paid')->sum('total_amount'),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_listings' => Listing::with('creator')->latest()->take(5)->get(),
            'recent_bookings' => Booking::with(['listing', 'user'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', [
            'stats' => $stats
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $resource)
    {
        // Ensure only SuperAdmin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        switch ($resource) {
            case 'users':
                $data = User::withCount(['listings', 'bookings'])
                    ->latest()
                    ->paginate(10);
                return view('admin.users', [
                    'users' => $data
                ]);
                
            case 'listings':
                $data = Listing::with(['creator'])
                    ->withCount('bookings')
                    ->latest()
                    ->paginate(10);
                return view('admin.listings', [
                    'listings' => $data
                ]);
                
            case 'bookings':
                $data = Booking::with(['listing', 'user'])
                    ->latest()
                    ->paginate(10);
                return view('admin.bookings', [
                    'bookings' => $data
                ]);
                
            default:
                abort(404);
        }
    }
}