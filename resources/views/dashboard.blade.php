@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar border-end">
                <div class="p-3">
                    <h5 class="fw-bold">Dashboard</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Overview
                        </a>
                        @if(auth()->user()->isCreator() || auth()->user()->isSuperAdmin())
                            <a class="nav-link" href="{{ route('listings.create') }}">
                                <i class="fas fa-plus me-2"></i>Create Listing
                            </a>
                        @endif
                        <a class="nav-link" href="{{ route('bookings.index') }}">
                            <i class="fas fa-calendar-check me-2"></i>My Bookings
                        </a>
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-cog me-2"></i>Profile Settings
                        </a>
                        @if(auth()->user()->isSuperAdmin())
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-crown me-2"></i>Admin Panel
                            </a>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-muted">Here's what's happening with your account</p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                @foreach($stats as $key => $value)
                    <div class="col-md-6 col-lg-3">
                        <div class="card stats-card text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h3 class="h2 fw-bold">{{ is_numeric($value) ? number_format($value) : $value }}</h3>
                                        <p class="mb-0">
                                            @switch($key)
                                                @case('total_users')
                                                    <i class="fas fa-users me-1"></i>Total Users
                                                    @break
                                                @case('total_listings')
                                                    <i class="fas fa-home me-1"></i>Total Listings
                                                    @break
                                                @case('total_bookings')
                                                    <i class="fas fa-calendar-check me-1"></i>Total Bookings
                                                    @break
                                                @case('total_revenue')
                                                    <i class="fas fa-dollar-sign me-1"></i>Total Revenue
                                                    @break
                                                @case('my_listings')
                                                    <i class="fas fa-home me-1"></i>My Listings
                                                    @break
                                                @case('my_bookings')
                                                    <i class="fas fa-calendar-check me-1"></i>My Bookings
                                                    @break
                                                @case('upcoming_bookings')
                                                    <i class="fas fa-clock me-1"></i>Upcoming
                                                    @break
                                                @default
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                            @endswitch
                                        </p>
                                    </div>
                                    <div class="opacity-75">
                                        @switch($key)
                                            @case('total_users')
                                                <i class="fas fa-users fa-2x"></i>
                                                @break
                                            @case('total_listings')
                                            @case('my_listings')
                                                <i class="fas fa-home fa-2x"></i>
                                                @break
                                            @case('total_bookings')
                                            @case('my_bookings')
                                                <i class="fas fa-calendar-check fa-2x"></i>
                                                @break
                                            @case('total_revenue')
                                                <i class="fas fa-dollar-sign fa-2x"></i>
                                                @break
                                            @case('upcoming_bookings')
                                                <i class="fas fa-clock fa-2x"></i>
                                                @break
                                            @default
                                                <i class="fas fa-chart-line fa-2x"></i>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Actions -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-rocket me-2 text-primary"></i>Quick Actions
                            </h5>
                            <div class="d-grid gap-2">
                                @if(auth()->user()->isCreator() || auth()->user()->isSuperAdmin())
                                    <a href="{{ route('listings.create') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-plus me-2"></i>Create New Listing
                                    </a>
                                @endif
                                <a href="{{ route('listings.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-search me-2"></i>Browse All Listings
                                </a>
                                <a href="{{ route('bookings.index') }}" class="btn btn-outline-info">
                                    <i class="fas fa-calendar-check me-2"></i>View My Bookings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-info-circle me-2 text-info"></i>Account Info
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <strong>Email:</strong> {{ auth()->user()->email }}
                                </li>
                                <li class="mb-2">
                                    <strong>Role:</strong> 
                                    <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Member since:</strong> {{ auth()->user()->created_at->format('M Y') }}
                                </li>
                            </ul>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fas fa-edit me-1"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection