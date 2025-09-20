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
                        <a class="nav-link active" href="{{ route('admin.show', 'listings') }}">
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
                    <h1 class="h3 fw-bold">🏠 Listing Management</h1>
                    <p class="text-muted">Manage all platform listings</p>
                </div>
            </div>

            @if($listings->count() > 0)
                <div class="row">
                    @foreach($listings as $listing)
                    <div class="col-lg-12 mb-4">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    @if($listing->image_path)
                                        <img src="{{ Storage::url($listing->image_path) }}" class="img-fluid rounded-start h-100" style="object-fit: cover; min-height: 150px;" alt="{{ $listing->title }}">
                                    @else
                                        <div class="bg-light rounded-start h-100 d-flex align-items-center justify-content-center" style="min-height: 150px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">{{ $listing->title }}</h5>
                                            <div>
                                                <span class="badge bg-{{ $listing->is_available ? 'success' : 'secondary' }} me-1">
                                                    {{ $listing->is_available ? 'Available' : 'Unavailable' }}
                                                </span>
                                                <span class="badge bg-light text-dark">{{ $listing->bookings_count ?? 0 }} bookings</span>
                                            </div>
                                        </div>
                                        
                                        <p class="card-text text-muted mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $listing->location }}
                                        </p>
                                        
                                        <p class="card-text mb-3">{{ Str::limit($listing->description, 120) }}</p>
                                        
                                        <div class="row text-sm mb-3">
                                            <div class="col-md-3">
                                                <strong>Host:</strong><br>
                                                <span class="text-muted">{{ $listing->creator->name }}</span>
                                                <br><span class="badge bg-{{ $listing->creator->role === 'SuperAdmin' ? 'danger' : 'warning' }} badge-sm">{{ $listing->creator->role }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Price:</strong><br>
                                                <span class="text-primary fw-bold">{{ $listing->formatted_price }}/day</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Created:</strong><br>
                                                <span class="text-muted">{{ $listing->created_at->format('M d, Y') }}</span>
                                                <br><small class="text-muted">{{ $listing->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Updated:</strong><br>
                                                <span class="text-muted">{{ $listing->updated_at->format('M d, Y') }}</span>
                                                <br><small class="text-muted">{{ $listing->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                            <a href="{{ route('listings.edit', $listing) }}" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <form method="POST" action="{{ route('listings.destroy', $listing) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                            </form>
                                        </div>
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
                    <h3>No listings found</h3>
                    <p class="text-muted">No listings have been created yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection