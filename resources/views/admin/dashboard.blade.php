@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar border-end">
                <div class="p-3">
                    <h5 class="fw-bold text-danger">
                        <i class="fas fa-crown me-2"></i>Admin Panel
                    </h5>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.show', 'users') }}">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a class="nav-link" href="{{ route('admin.show', 'listings') }}">
                            <i class="fas fa-home me-2"></i>Listings
                        </a>
                        <a class="nav-link" href="{{ route('admin.show', 'bookings') }}">
                            <i class="fas fa-calendar-check me-2"></i>Bookings
                        </a>
                        <hr>
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">Admin Dashboard 👑</h1>
                    <p class="text-muted">System overview and management</p>
                </div>
                <div>
                    <span class="badge bg-danger fs-6">Super Admin</span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="card stats-card text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="h2 fw-bold">{{ number_format($stats['total_users']) }}</h3>
                                    <p class="mb-0">
                                        <i class="fas fa-users me-1"></i>Total Users
                                    </p>
                                </div>
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card stats-card text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="h2 fw-bold">{{ number_format($stats['total_listings']) }}</h3>
                                    <p class="mb-0">
                                        <i class="fas fa-home me-1"></i>Total Listings
                                    </p>
                                </div>
                                <i class="fas fa-home fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card stats-card text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="h2 fw-bold">{{ number_format($stats['total_bookings']) }}</h3>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar-check me-1"></i>Total Bookings
                                    </p>
                                </div>
                                <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card stats-card text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="h2 fw-bold">${{ number_format($stats['total_revenue'], 2) }}</h3>
                                    <p class="mb-0">
                                        <i class="fas fa-dollar-sign me-1"></i>Total Revenue
                                    </p>
                                </div>
                                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Users -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2 text-primary"></i>Recent Users
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($stats['recent_users']->count() > 0)
                                @foreach($stats['recent_users'] as $user)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                        <br><span class="badge bg-{{ $user->role === 'SuperAdmin' ? 'danger' : ($user->role === 'Creator' ? 'warning' : 'info') }} badge-sm">{{ $user->role }}</span>
                                    </div>
                                </div>
                                @endforeach
                                <div class="text-center">
                                    <a href="{{ route('admin.show', 'users') }}" class="btn btn-outline-primary btn-sm">View All Users</a>
                                </div>
                            @else
                                <p class="text-muted text-center">No users yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Listings -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-home me-2 text-success"></i>Recent Listings
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($stats['recent_listings']->count() > 0)
                                @foreach($stats['recent_listings'] as $listing)
                                <div class="mb-3">
                                    <h6 class="mb-1">{{ Str::limit($listing->title, 30) }}</h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $listing->location }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">by {{ $listing->creator->name }}</small>
                                        <span class="badge bg-{{ $listing->is_available ? 'success' : 'secondary' }} badge-sm">
                                            {{ $listing->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                                <div class="text-center">
                                    <a href="{{ route('admin.show', 'listings') }}" class="btn btn-outline-success btn-sm">View All Listings</a>
                                </div>
                            @else
                                <p class="text-muted text-center">No listings yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-check me-2 text-info"></i>Recent Bookings
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($stats['recent_bookings']->count() > 0)
                                @foreach($stats['recent_bookings'] as $booking)
                                <div class="mb-3">
                                    <h6 class="mb-1">{{ Str::limit($booking->listing->title, 25) }}</h6>
                                    <p class="mb-1 text-muted small">{{ $booking->user->name }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">${{ number_format($booking->total_amount, 2) }}</small>
                                        <span class="badge payment-{{ strtolower($booking->payment_status) }} badge-sm">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                                <div class="text-center">
                                    <a href="{{ route('admin.show', 'bookings') }}" class="btn btn-outline-info btn-sm">View All Bookings</a>
                                </div>
                            @else
                                <p class="text-muted text-center">No bookings yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection