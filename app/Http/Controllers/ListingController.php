<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Models\Listing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listings = Listing::with('creator')
            ->available()
            ->latest()
            ->paginate(12);
        
        return Inertia::render('listings/index', [
            'listings' => $listings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('listings/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreListingRequest $request)
    {
        $data = $request->validated();
        $data['creator_id'] = auth()->id();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('listings', 'public');
        }

        $listing = Listing::create($data);

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Listing created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        $listing->load('creator');
        
        return Inertia::render('listings/show', [
            'listing' => $listing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        // Ensure only the creator can edit
        if ($listing->creator_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        return Inertia::render('listings/edit', [
            'listing' => $listing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateListingRequest $request, Listing $listing)
    {
        // Ensure only the creator can update
        if ($listing->creator_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $data = $request->validated();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('listings', 'public');
        }

        $listing->update($data);

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Listing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        // Ensure only the creator can delete
        if ($listing->creator_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $listing->delete();

        return redirect()->route('listings.index')
            ->with('success', 'Listing deleted successfully.');
    }
}