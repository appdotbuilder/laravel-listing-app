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
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.show', 'users') }}">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a class="nav-link" href="{{ route('admin.show', 'listings') }}">
                            <i class="fas fa-home me-2"></i>Listings
                        </a>
                        <a class="nav-link active" href="{{ route('admin.show', 'bookings') }}">
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
                    <h1 class="h3 fw-bold">📅 Booking Management</h1>
                    <p class="text-muted">Manage all platform bookings</p>
                </div>
            </div>

            @if($bookings->count() > 0)
                <div class="row">
                    @foreach($bookings as $booking)
                    <div class="col-lg-12 mb-4">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    @if($booking->listing->image_path)
                                        <img src="{{ Storage::url($booking->listing->image_path) }}" class="img-fluid rounded-start h-100" style="object-fit: cover; min-height: 180px;" alt="{{ $booking->listing->title }}">
                                    @else
                                        <div class="bg-light rounded-start h-100 d-flex align-items-center justify-content-center" style="min-height: 180px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="card-title mb-0">{{ $booking->listing->title }}</h5>
                                                <p class="card-text text-muted mb-0">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $booking->listing->location }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge booking-{{ strtolower($booking->status) }} status-badge me-1">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                                <span class="badge payment-{{ strtolower($booking->payment_status) }} status-badge">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-sm mb-3">
                                            <div class="col-md-3">
                                                <strong>Guest:</strong><br>
                                                <span class="text-muted">{{ $booking->user->name }}</span>
                                                <br><small class="text-muted">{{ $booking->user->email }}</small>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Host:</strong><br>
                                                <span class="text-muted">{{ $booking->listing->creator->name }}</span>
                                                <br><span class="badge bg-{{ $booking->listing->creator->role === 'SuperAdmin' ? 'danger' : 'warning' }} badge-sm">{{ $booking->listing->creator->role }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Dates:</strong><br>
                                                <span class="text-muted">{{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}</span>
                                                <br><small class="text-muted">{{ $booking->days }} night{{ $booking->days > 1 ? 's' : '' }}</small>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Amount:</strong><br>
                                                <span class="h5 text-primary">{{ $booking->formatted_total }}</span>
                                                <br><small class="text-muted">${{ number_format($booking->listing->price_per_day, 2) }}/night</small>
                                            </div>
                                        </div>

                                        @if($booking->payment_gateway || $booking->payment_id)
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    @if($booking->payment_gateway)
                                                        <strong>Payment Method:</strong> {{ ucfirst($booking->payment_gateway) }}
                                                    @endif
                                                    @if($booking->payment_id)
                                                        | <strong>Transaction ID:</strong> <code>{{ $booking->payment_id }}</code>
                                                    @endif
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small">
                                                <i class="fas fa-calendar me-1"></i>
                                                Booked {{ $booking->created_at->diffForHumans() }}
                                                @if($booking->updated_at != $booking->created_at)
                                                    | Updated {{ $booking->updated_at->diffForHumans() }}
                                                @endif
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View Details
                                                </a>
                                                <a href="{{ route('listings.show', $booking->listing) }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-home me-1"></i>View Listing
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h3>No bookings found</h3>
                    <p class="text-muted">No bookings have been made yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection