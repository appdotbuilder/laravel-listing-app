<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['listing', 'listing.creator'])
            ->latest()
            ->paginate(10);
        
        return view('bookings.index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $listing = Listing::with('creator')->findOrFail($request->listing_id);
        
        return view('bookings.create', [
            'listing' => $listing
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Calculate total amount based on listing price and days
        $listing = Listing::findOrFail($data['listing_id']);
        $startDate = new \DateTime($data['start_date']);
        $endDate = new \DateTime($data['end_date']);
        $days = $startDate->diff($endDate)->days + 1;
        $data['total_amount'] = $listing->price_per_day * $days;

        $booking = Booking::create($data);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully. Please proceed with payment.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $booking->load(['listing', 'listing.creator', 'user']);
        
        return view('bookings.show', [
            'booking' => $booking
        ]);
    }

    /**
     * Update the booking payment status.
     */
    public function update(Request $request, Booking $booking)
    {
        // Ensure user can only update their own bookings
        if ($booking->user_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_gateway' => 'nullable|string',
            'payment_id' => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // Ensure user can only cancel their own bookings
        if ($booking->user_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}