@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4 class="mb-0"><i class="fas fa-envelope-circle-check me-2"></i>Verify Email</h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <p class="lead">
                            Thanks for signing up! Before getting started, could you verify your email address 
                            by clicking on the link we just emailed to you?
                        </p>
                        <p class="text-muted">
                            If you didn't receive the email, we will gladly send you another.
                        </p>
                    </div>

                    @if(session('status') === 'verification-link-sent')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            A new verification link has been sent to your email address.
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Resend Email
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="fas fa-sign-out-alt me-2"></i>Log Out
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection