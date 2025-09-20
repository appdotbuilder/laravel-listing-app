@extends('layouts.app')

@section('content')
<div class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">🏠 Find Your Perfect Stay</h1>
                <p class="lead mb-4">Discover amazing places to stay and create unforgettable memories with our curated selection of accommodations.</p>
                
                <div class="mb-4">
                    <h5><i class="fas fa-check-circle me-2"></i>Key Features:</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-home me-2"></i>Browse thousands of unique properties</li>
                        <li><i class="fas fa-calendar-check me-2"></i>Easy booking and instant confirmation</li>
                        <li><i class="fas fa-shield-alt me-2"></i>Secure payment processing</li>
                        <li><i class="fas fa-users me-2"></i>24/7 customer support</li>
                    </ul>
                </div>
                
                @guest
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-sign-in-alt me-2"></i>Log In
                        </a>
                    </div>
                @else
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('listings.index') }}" class="btn btn-warning btn-lg px-4">
                            <i class="fas fa-search me-2"></i>Browse Listings
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </div>
                @endguest
            </div>
            <div class="col-lg-6 text-center">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white bg-opacity-25 rounded p-4">
                            <i class="fas fa-home fa-3x mb-2"></i>
                            <h4>Find Homes</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-25 rounded p-4">
                            <i class="fas fa-calendar-check fa-3x mb-2"></i>
                            <h4>Book Easily</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-25 rounded p-4">
                            <i class="fas fa-shield-alt fa-3x mb-2"></i>
                            <h4>Stay Safe</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-25 rounded p-4">
                            <i class="fas fa-heart fa-3x mb-2"></i>
                            <h4>Enjoy</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($listings->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h1 fw-bold">✨ Featured Listings</h2>
            <p class="lead text-muted">Discover our most popular accommodations</p>
        </div>
        
        <div class="row g-4">
            @foreach($listings as $listing)
            <div class="col-md-6 col-lg-4">
                <div class="card listing-card h-100">
                    @if($listing->image_path)
                        <img src="{{ Storage::url($listing->image_path) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $listing->title }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $listing->title }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($listing->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-primary">{{ $listing->formatted_price }}/day</strong>
                                <small class="text-muted d-block">📍 {{ $listing->location }}</small>
                            </div>
                            <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-primary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('listings.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>View All Listings
            </a>
        </div>
    </div>
</section>
@endif

<section class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h1 fw-bold">🎯 Why Choose Us?</h2>
            <p class="lead text-muted">We make booking accommodations simple and secure</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-search fa-3x text-primary mb-3"></i>
                        <h4>Easy Discovery</h4>
                        <p class="text-muted">Browse through thousands of verified listings with detailed photos and descriptions.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-credit-card fa-3x text-success mb-3"></i>
                        <h4>Secure Payments</h4>
                        <p class="text-muted">Your payments are processed securely with industry-standard encryption.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-headset fa-3x text-info mb-3"></i>
                        <h4>24/7 Support</h4>
                        <p class="text-muted">Our customer support team is available around the clock to help you.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection