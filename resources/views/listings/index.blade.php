@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">🏠 Browse Listings</h1>
            <p class="text-muted">Discover amazing places to stay</p>
        </div>
        @auth
            @if(auth()->user()->isCreator() || auth()->user()->isSuperAdmin())
                <a href="{{ route('listings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Listing
                </a>
            @endif
        @endauth
    </div>

    @if($listings->count() > 0)
        <div class="row g-4">
            @foreach($listings as $listing)
            <div class="col-md-6 col-lg-4">
                <div class="card listing-card h-100">
                    @if($listing->image_path)
                        <img src="{{ Storage::url($listing->image_path) }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="{{ $listing->title }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $listing->title }}</h5>
                            @if($listing->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Unavailable</span>
                            @endif
                        </div>
                        <p class="card-text flex-grow-1">{{ Str::limit($listing->description, 120) }}</p>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $listing->location }}
                            </small>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>by {{ $listing->creator->name }}
                            </small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-primary h5">{{ $listing->formatted_price }}</strong>
                                <small class="text-muted">/day</small>
                            </div>
                            <div>
                                <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $listings->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-home fa-4x text-muted mb-3"></i>
            <h3>No listings available</h3>
            <p class="text-muted">Check back later for new listings.</p>
            @auth
                @if(auth()->user()->isCreator() || auth()->user()->isSuperAdmin())
                    <a href="{{ route('listings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Listing
                    </a>
                @endif
            @endauth
        </div>
    @endif
</div>
@endsection