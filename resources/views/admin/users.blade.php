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
                        <a class="nav-link active" href="{{ route('admin.show', 'users') }}">
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
                    <h1 class="h3 fw-bold">👥 User Management</h1>
                    <p class="text-muted">Manage all platform users</p>
                </div>
            </div>

            @if($users->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">All Users ({{ $users->total() }})</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Listings</th>
                                    <th>Bookings</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">#{{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $user->email }}</span>
                                        @if($user->email_verified_at)
                                            <i class="fas fa-check-circle text-success ms-1" title="Email verified"></i>
                                        @else
                                            <i class="fas fa-exclamation-circle text-warning ms-1" title="Email not verified"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'SuperAdmin' ? 'danger' : ($user->role === 'Creator' ? 'warning' : 'info') }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->role === 'Creator' || $user->role === 'SuperAdmin')
                                            <span class="badge bg-light text-dark">{{ $user->listings_count ?? 0 }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $user->bookings_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span>
                                        <br><small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h3>No users found</h3>
                    <p class="text-muted">No users have registered yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection