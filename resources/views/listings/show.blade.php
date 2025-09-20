@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('listings.index') }}">Listings</a></li>
            <li class="breadcrumb-item active">{{ $listing->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                @if($listing->image_path)
                    <img src="{{ Storage::url($listing->image_path) }}" class="card-img-top" style="height: 400px; object-fit: cover;" alt="{{ $listing->title }}">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h3 fw-bold">{{ $listing->title }}</h1>
                            <p class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $listing->location }}
                            </p>
                        </div>
                        @if($listing->is_available)
                            <span class="badge bg-success fs-6">Available</span>
                        @else
                            <span class="badge bg-secondary fs-6">Unavailable</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="lead">{{ $listing->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5>Host Information</h5>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $listing->creator->name }}</h6>
                                <small class="text-muted">Host since {{ $listing->creator->created_at->format('M Y') }}</small>
                            </div>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->id === $listing->creator_id || auth()->user()->isSuperAdmin())
                            <div class="border-top pt-3">
                                <h6 class="text-muted">Listing Management</h6>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('listings.edit', $listing) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Edit Listing
                                    </a>
                                    <form method="POST" action="{{ route('listings.destroy', $listing) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Delete Listing
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">{{ $listing->formatted_price }}</h3>
                        <small class="text-muted">per day</small>
                    </div>

                    @auth
                        @if(auth()->user()->id !== $listing->creator_id && $listing->is_available)
                            <a href="{{ route('bookings.create', ['listing_id' => $listing->id]) }}" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-calendar-plus me-2"></i>Book Now
                            </a>
                            <p class="text-center text-muted small">You won't be charged yet</p>
                        @elseif(auth()->user()->id === $listing->creator_id)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>This is your listing
                            </div>
                        @elseif(!$listing->is_available)
                            <button class="btn btn-secondary w-100" disabled>
                                Currently Unavailable
                            </button>
                        @endif
                    @else
                        <div class="text-center">
                            <p class="text-muted mb-3">To book this listing, please:</p>
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">Log In</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Register</a>
                        </div>
                    @endauth

                    <hr>

                    <div class="mb-3">
                        <h6><i class="fas fa-info-circle me-2"></i>Listing Details</h6>
                        <ul class="list-unstyled small text-muted">
                            <li><strong>Listed:</strong> {{ $listing->created_at->format('M d, Y') }}</li>
                            <li><strong>Updated:</strong> {{ $listing->updated_at->format('M d, Y') }}</li>
                            <li><strong>ID:</strong> #{{ $listing->id }}</li>
                        </ul>
                    </div>

                    <div>
                        <h6><i class="fas fa-shield-alt me-2"></i>Booking Protection</h6>
                        <ul class="list-unstyled small text-muted">
                            <li><i class="fas fa-check text-success me-1"></i>Secure payment processing</li>
                            <li><i class="fas fa-check text-success me-1"></i>24/7 customer support</li>
                            <li><i class="fas fa-check text-success me-1"></i>Free cancellation policy</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection