<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        
        return view('listings.index', [
            'listings' => $listings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('listings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreListingRequest $request)
    {
        try {
            $data = $request->validated();
            $data['creator_id'] = auth()->id();

            // Handle is_available field - default to true if not provided
            $data['is_available'] = $request->has('is_available') ? 
                (bool) $request->input('is_available') : true;

            // Handle image upload if present
            if ($request->hasFile('image')) {
                try {
                    $image = $request->file('image');
                    
                    // Validate the uploaded file
                    if (!$image->isValid()) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['image' => 'The uploaded image file is corrupted or invalid. Please try uploading a different image.']);
                    }

                    // Check if the storage directory is writable
                    $storagePath = storage_path('app/public/listings');
                    if (!is_dir($storagePath)) {
                        if (!mkdir($storagePath, 0755, true)) {
                            return redirect()->back()
                                ->withInput()
                                ->withErrors(['image' => 'Unable to create storage directory for images. Please contact support if this issue persists.']);
                        }
                    }

                    if (!is_writable($storagePath)) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['image' => 'Image storage directory is not writable. Please contact support to resolve this issue.']);
                    }

                    // Attempt to store the image
                    $imagePath = $image->store('listings', 'public');
                    
                    if (!$imagePath) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['image' => 'Failed to save the uploaded image. Please try again with a different image file.']);
                    }

                    $data['image_path'] = $imagePath;

                } catch (\Exception $e) {
                    \Log::error('Image upload error in ListingController@store: ' . $e->getMessage(), [
                        'user_id' => auth()->id(),
                        'file_size' => $request->file('image')?->getSize(),
                        'file_type' => $request->file('image')?->getMimeType(),
                        'original_name' => $request->file('image')?->getClientOriginalName(),
                    ]);

                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'There was an error processing your image upload. Please ensure your image file is not corrupted and try again.']);
                }
            }

            // Create the listing
            $listing = Listing::create($data);

            return redirect()->route('listings.show', $listing)
                ->with('success', 'Listing created successfully.');

        } catch (\Exception $e) {
            \Log::error('Error creating listing in ListingController@store: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'There was an error creating your listing. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        $listing->load('creator');
        
        return view('listings.show', [
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

        return view('listings.edit', [
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

        try {
            $data = $request->validated();

            // Handle is_available field - preserve existing value if not provided
            if ($request->has('is_available')) {
                $data['is_available'] = (bool) $request->input('is_available');
            }

            // Handle image upload if present
            if ($request->hasFile('image')) {
                try {
                    $image = $request->file('image');
                    
                    // Validate the uploaded file
                    if (!$image->isValid()) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['image' => 'The uploaded image file is corrupted or invalid. Please try uploading a different image.']);
                    }

                    // Check if the storage directory is writable
                    $storagePath = storage_path('app/public/listings');
                    if (!is_dir($storagePath)) {
                        if (!mkdir($storagePath, 0755, true)) {
                            return redirect()->back()
                                ->withInput()
                                ->withErrors(['image' => 'Unable to create storage directory for images. Please contact support if this issue persists.']);
                        }
                    }

                    if (!is_writable($storagePath)) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['image' => 'Image storage directory is not writable. Please contact support to resolve this issue.']);
                    }

                    // Store the old image path to potentially delete it later
                    $oldImagePath = $listing->image_path;

                    // Attempt to store the new image
                    $imagePath = $image->store('listings', 'public');
                    
                    if (!$imagePath) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['image' => 'Failed to save the uploaded image. Please try again with a different image file.']);
                    }

                    $data['image_path'] = $imagePath;

                    // Delete the old image file if it exists and the new one was stored successfully
                    if ($oldImagePath && \Storage::disk('public')->exists($oldImagePath)) {
                        try {
                            \Storage::disk('public')->delete($oldImagePath);
                        } catch (\Exception $e) {
                            // Log but don't fail the update if old image deletion fails
                            \Log::warning('Failed to delete old image during listing update: ' . $e->getMessage(), [
                                'listing_id' => $listing->id,
                                'old_image_path' => $oldImagePath,
                            ]);
                        }
                    }

                } catch (\Exception $e) {
                    \Log::error('Image upload error in ListingController@update: ' . $e->getMessage(), [
                        'listing_id' => $listing->id,
                        'user_id' => auth()->id(),
                        'file_size' => $request->file('image')?->getSize(),
                        'file_type' => $request->file('image')?->getMimeType(),
                        'original_name' => $request->file('image')?->getClientOriginalName(),
                    ]);

                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'There was an error processing your image upload. Please ensure your image file is not corrupted and try again.']);
                }
            }

            // Update the listing
            $listing->update($data);

            return redirect()->route('listings.show', $listing)
                ->with('success', 'Listing updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Error updating listing in ListingController@update: ' . $e->getMessage(), [
                'listing_id' => $listing->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'There was an error updating your listing. Please try again.']);
        }
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