@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">My Bookings</a></li>
            <li class="breadcrumb-item active">Booking #{{ $booking->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Booking Details
                        </h4>
                        <div>
                            <span class="badge booking-{{ strtolower($booking->status) }} status-badge me-1 fs-6">
                                {{ ucfirst($booking->status) }}
                            </span>
                            <span class="badge payment-{{ strtolower($booking->payment_status) }} status-badge fs-6">
                                Payment: {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($booking->listing->image_path)
                                <img src="{{ Storage::url($booking->listing->image_path) }}" class="img-fluid rounded" alt="{{ $booking->listing->title }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $booking->listing->title }}</h3>
                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $booking->listing->location }}
                            </p>
                            <p class="mb-3">{{ Str::limit($booking->listing->description, 200) }}</p>
                            
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $booking->listing->creator->name }}</h6>
                                    <small class="text-muted">Host</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-calendar me-2 text-primary"></i>Stay Details</h5>
                            <div class="mb-2">
                                <strong>Check-in:</strong>
                                <br><span class="text-muted">{{ $booking->start_date->format('l, F j, Y') }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Check-out:</strong>
                                <br><span class="text-muted">{{ $booking->end_date->format('l, F j, Y') }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Duration:</strong>
                                <br><span class="text-muted">{{ $booking->days }} night{{ $booking->days > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5><i class="fas fa-credit-card me-2 text-success"></i>Payment Information</h5>
                            <div class="mb-2">
                                <strong>Total Amount:</strong>
                                <br><span class="h4 text-primary">{{ $booking->formatted_total }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Payment Status:</strong>
                                <br><span class="badge payment-{{ strtolower($booking->payment_status) }} status-badge">{{ ucfirst($booking->payment_status) }}</span>
                            </div>
                            @if($booking->payment_gateway)
                                <div class="mb-2">
                                    <strong>Payment Method:</strong>
                                    <br><span class="text-muted">{{ ucfirst($booking->payment_gateway) }}</span>
                                </div>
                            @endif
                            @if($booking->payment_id)
                                <div class="mb-2">
                                    <strong>Transaction ID:</strong>
                                    <br><code>{{ $booking->payment_id }}</code>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5><i class="fas fa-info-circle me-2 text-info"></i>Booking Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Booking ID:</strong>
                                <br><code>#{{ $booking->id }}</code>
                            </div>
                            <div class="col-md-4">
                                <strong>Booked on:</strong>
                                <br><span class="text-muted">{{ $booking->created_at->format('M j, Y \a\t g:i A') }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Last updated:</strong>
                                <br><span class="text-muted">{{ $booking->updated_at->format('M j, Y \a\t g:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($booking->status !== 'cancelled' && $booking->payment_status === 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Payment Pending:</strong> Please complete your payment to confirm this booking.
                        </div>
                    @endif

                    <div class="d-flex gap-2 flex-wrap">
                        @if($booking->payment_status === 'pending' && $booking->status !== 'cancelled')
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                <i class="fas fa-credit-card me-2"></i>Update Payment
                            </button>
                        @endif
                        
                        @if($booking->status !== 'cancelled' && $booking->payment_status === 'pending')
                            <form method="POST" action="{{ route('bookings.destroy', $booking) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-times me-2"></i>Cancel Booking
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                        </a>
                        <a href="{{ route('listings.show', $booking->listing) }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-2"></i>View Listing
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Price Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>${{ number_format($booking->listing->price_per_day, 2) }} × {{ $booking->days }} night{{ $booking->days > 1 ? 's' : '' }}</span>
                        <span>${{ number_format($booking->listing->price_per_day * $booking->days, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span class="text-primary">{{ $booking->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Booking Protection</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Free cancellation for pending bookings
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>24/7 customer support
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>Secure payment processing
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>Host verification
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Update Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('bookings.update', $booking) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Payment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $booking->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_gateway" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_gateway" name="payment_gateway">
                            <option value="">Select method</option>
                            <option value="stripe" {{ $booking->payment_gateway === 'stripe' ? 'selected' : '' }}>Credit Card (Stripe)</option>
                            <option value="paypal" {{ $booking->payment_gateway === 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="cash" {{ $booking->payment_gateway === 'cash' ? 'selected' : '' }}>Cash</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_id" class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" id="payment_id" name="payment_id" value="{{ $booking->payment_id }}" placeholder="Optional transaction reference">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection