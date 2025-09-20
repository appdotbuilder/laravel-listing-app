@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar border-end">
                <div class="p-3">
                    <h5 class="fw-bold">Settings</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                        <a class="nav-link" href="{{ route('password.edit') }}">
                            <i class="fas fa-lock me-2"></i>Password
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
                    <h1 class="h3 fw-bold">👤 Profile Settings</h1>
                    <p class="text-muted">Update your account information</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Profile Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            @if(session('status') === 'profile-updated')
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="fas fa-check-circle me-2"></i>Profile updated successfully.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if(auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                                        <div class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Your email address is unverified.
                                            <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0 text-decoration-none">
                                                    Click here to re-send the verification email.
                                                </button>
                                            </form>
                                        </div>
                                        
                                        @if(session('status') === 'verification-link-sent')
                                            <div class="form-text text-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                A new verification link has been sent to your email address.
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Account Type</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->role) }}" readonly>
                                    <div class="form-text">
                                        Contact support if you need to change your account type.
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h5>
                        </div>
                        <div class="card-body">
                            <h6>Delete Account</h6>
                            <p class="text-muted">
                                Once your account is deleted, all of its resources and data will be permanently deleted. 
                                Before deleting your account, please download any data or information that you wish to retain.
                            </p>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fas fa-trash me-2"></i>Delete Account
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                                <h5 class="mt-2">{{ auth()->user()->name }}</h5>
                                <span class="badge bg-{{ auth()->user()->role === 'SuperAdmin' ? 'danger' : (auth()->user()->role === 'Creator' ? 'warning' : 'info') }} fs-6">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Email:</span>
                                    <span>
                                        @if(auth()->user()->email_verified_at)
                                            <i class="fas fa-check-circle text-success me-1"></i>Verified
                                        @else
                                            <i class="fas fa-exclamation-circle text-warning me-1"></i>Unverified
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Member since:</span>
                                    <span>{{ auth()->user()->created_at->format('M Y') }}</span>
                                </div>
                            </div>

                            @if(auth()->user()->isCreator() || auth()->user()->isSuperAdmin())
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Listings:</span>
                                        <span>{{ auth()->user()->listings()->count() }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Bookings:</span>
                                    <span>{{ auth()->user()->bookings()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>
                    <p>Are you sure you want to delete your account? All of your data will be permanently deleted.</p>
                    <div class="mb-3">
                        <label for="password" class="form-label">Please enter your password to confirm:</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection