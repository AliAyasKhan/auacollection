@extends('layouts.store')

@section('title', 'My Account - AUA Collection')

@section('content')

    <!-- Header -->
    <section class="py-4 bg-light">
        <div class="container">
            <h1 class="font-serif mb-0 fs-3">My Account</h1>
        </div>
    </section>

    <!-- Content -->
    <section class="py-5">
        <div class="container">
            
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 mb-4 text-center">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-4 small">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                
                <!-- Account Sidebar Navigation -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 dashboard-sidebar">
                        <div class="text-center mb-4 pb-3 border-bottom">
                            <h5 class="mb-1 font-heading fs-6">{{ auth()->user()->name }}</h5>
                            <span class="text-muted small text-truncate d-block">{{ auth()->user()->email }}</span>
                        </div>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('account.dashboard') }}"><i class="bi bi-person-fill me-2"></i>Profile Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('account.orders') }}"><i class="bi bi-bag-fill me-2"></i>My Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('account.track') }}"><i class="bi bi-geo-alt-fill me-2"></i>Track Order</a>
                            </li>
                            <li class="nav-item mt-3 pt-3 border-top">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger text-start nav-link w-100 p-0 ps-3 border-0"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Account Profile Forms -->
                <div class="col-md-9">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h4 class="font-heading fs-5 pb-2 mb-4 border-bottom border-light">PROFILE DETAILS</h4>
                        
                        <form action="{{ route('account.update') }}" method="POST">
                            @csrf
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>

                            <h4 class="font-heading fs-5 pb-2 mb-4 border-bottom border-light">CHANGE PASSWORD</h4>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Enter current password if changing">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new password">
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn-luxury-dark py-2.5">SAVE CHANGES</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
