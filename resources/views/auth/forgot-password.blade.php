<x-guest-layout>
    <x-auth-session-status class="mb-3" :status="session('status')" />
    
    {{-- Toastr messages --}}
    {!! Toastr::message() !!}

    <div class="text-center mb-4">
        <h4 class="mb-1">Forgot Password</h4>
        <p class="text-muted small mb-0">Enter your email to receive a reset link</p>
    </div>

    <form method="POST" action="{{ route('forget-password') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none small">Back to Login</a>
        </div>
    </form>
</x-guest-layout>
