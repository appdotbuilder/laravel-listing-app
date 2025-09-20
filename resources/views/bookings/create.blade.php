@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('listings.show', $listing) }}">{{ Str::limit($listing->title, 30) }}</a></li>
            <li class="breadcrumb-item active">Book Now</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Book Your Stay</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">

                        <div class="mb-4">
                            <h5>Booking Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">Check-out Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Price Calculation:</strong> $<span id="pricePerDay">{{ number_format($listing->price_per_day, 2) }}</span>/day × 
                                <span id="totalDays">1</span> day(s) = $<span id="totalAmount">{{ number_format($listing->price_per_day, 2) }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Payment Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payment_status" class="form-label">Payment Status</label>
                                    <select class="form-select" id="payment_status" name="payment_status">
                                        <option value="pending" selected>Pending Payment</option>
                                        <option value="paid">Mark as Paid</option>
                                    </select>
                                    <div class="form-text">You can process payment later if selecting "Pending"</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="payment_gateway" class="form-label">Payment Method (Optional)</label>
                                    <select class="form-select" id="payment_gateway" name="payment_gateway">
                                        <option value="">Select payment method</option>
                                        <option value="stripe">Credit Card (Stripe)</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="payment_id" class="form-label">Payment Reference (Optional)</label>
                                <input type="text" class="form-control" id="payment_id" name="payment_id" 
                                       placeholder="Transaction ID or reference number">
                            </div>
                        </div>

                        @if($errors->has('general'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('general') }}
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-check me-2"></i>Complete Booking
                            </button>
                            <a href="{{ route('listings.show', $listing) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Listing
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        @if($listing->image_path)
                            <img src="{{ Storage::url($listing->image_path) }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $listing->title }}">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $listing->title }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $listing->location }}
                            </small>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Host:</span>
                            <span>{{ $listing->creator->name }}</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Price per day:</span>
                            <span>${{ number_format($listing->price_per_day, 2) }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span class="text-primary" id="summaryTotal">${{ number_format($listing->price_per_day, 2) }}</span>
                    </div>

                    <div class="mt-3">
                        <h6><i class="fas fa-shield-alt me-2 text-success"></i>Your Booking is Protected</h6>
                        <ul class="list-unstyled small text-muted">
                            <li><i class="fas fa-check text-success me-1"></i>Free cancellation</li>
                            <li><i class="fas fa-check text-success me-1"></i>24/7 customer support</li>
                            <li><i class="fas fa-check text-success me-1"></i>Secure payment processing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalDaysSpan = document.getElementById('totalDays');
    const totalAmountSpan = document.getElementById('totalAmount');
    const summaryTotal = document.getElementById('summaryTotal');
    const pricePerDay = {{ $listing->price_per_day }};

    function calculateTotal() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate && endDate && endDate > startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const total = pricePerDay * daysDiff;
            
            totalDaysSpan.textContent = daysDiff;
            totalAmountSpan.textContent = total.toFixed(2);
            summaryTotal.textContent = '$' + total.toFixed(2);
        } else {
            totalDaysSpan.textContent = '1';
            totalAmountSpan.textContent = pricePerDay.toFixed(2);
            summaryTotal.textContent = '$' + pricePerDay.toFixed(2);
        }
    }

    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const nextDay = new Date(startDate);
        nextDay.setDate(nextDay.getDate() + 1);
        endDateInput.min = nextDay.toISOString().split('T')[0];
        
        if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
            endDateInput.value = nextDay.toISOString().split('T')[0];
        }
        
        calculateTotal();
    });

    endDateInput.addEventListener('change', calculateTotal);
});
</script>
@endpush
@endsection