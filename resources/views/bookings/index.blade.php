@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">📅 My Bookings</h1>
            <p class="text-muted">Manage your reservations</p>
        </div>
        <a href="{{ route('listings.index') }}" class="btn btn-primary">
            <i class="fas fa-search me-2"></i>Browse Listings
        </a>
    </div>

    @if($bookings->count() > 0)
        <div class="row">
            @foreach($bookings as $booking)
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            @if($booking->listing->image_path)
                                <img src="{{ Storage::url($booking->listing->image_path) }}" class="img-fluid rounded-start h-100" style="object-fit: cover; min-height: 200px;" alt="{{ $booking->listing->title }}">
                            @else
                                <div class="bg-light rounded-start h-100 d-flex align-items-center justify-content-center" style="min-height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card-body h-100 d-flex flex-column">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title">{{ $booking->listing->title }}</h5>
                                        <div>
                                            <span class="badge booking-{{ strtolower($booking->status) }} status-badge me-1">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            <span class="badge payment-{{ strtolower($booking->payment_status) }} status-badge">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <p class="card-text text-muted mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $booking->listing->location }}
                                    </p>

                                    <div class="row text-sm mb-3">
                                        <div class="col-md-4">
                                            <strong>Check-in:</strong><br>
                                            <i class="fas fa-calendar me-1 text-primary"></i>{{ $booking->start_date->format('M d, Y') }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Check-out:</strong><br>
                                            <i class="fas fa-calendar me-1 text-danger"></i>{{ $booking->end_date->format('M d, Y') }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Duration:</strong><br>
                                            <i class="fas fa-clock me-1 text-info"></i>{{ $booking->days }} day{{ $booking->days > 1 ? 's' : '' }}
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="text-primary mb-0">{{ $booking->formatted_total }}</h5>
                                            <small class="text-muted">Total amount</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                                                View Details
                                            </a>
                                            @if($booking->status !== 'cancelled' && $booking->payment_status === 'pending')
                                                <form method="POST" action="{{ route('bookings.destroy', $booking) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
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
            <h3>No bookings yet</h3>
            <p class="text-muted mb-4">Start exploring our amazing listings and make your first reservation!</p>
            <a href="{{ route('listings.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>Browse Listings
            </a>
        </div>
    @endif
</div>
@endsection