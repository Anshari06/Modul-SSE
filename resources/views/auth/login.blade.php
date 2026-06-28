@extends('layouts.main')

@section('title', 'Login Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card card-queue">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">
                            <i class="bi bi-shield-lock-fill me-2"></i>Login Admin
                        </h3>
                        <p class="text-muted mb-0">Masuk untuk mengelola antrian</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email') }}" placeholder="admin@email.com" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Masukkan password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-queue">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <a href="{{ route('guest') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection