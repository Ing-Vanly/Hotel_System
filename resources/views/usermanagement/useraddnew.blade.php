@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">
                            <i class="fas fa-user-plus mr-2"></i>Add New User
                        </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('users/list/page') }}">Users</a></li>
                                <li class="breadcrumb-item active">Add New User</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Add Form -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user mr-2"></i>User Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users/store') }}" method="POST" enctype="multipart/form-data"
                                id="addUserForm">
                                @csrf

                                <!-- Profile Image Section -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <div class="profile-upload">
                                                <div class="upload-img">
                                                    <img id="preview-image" src="{{ asset('assets/img/placeholder.jpg') }}"
                                                        alt="User Image" class="rounded-circle"
                                                        style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #dee2e6;">
                                                </div>
                                                <div class="upload-input">
                                                    <label for="image" class="btn btn-outline-primary btn-sm mt-2">
                                                        <i class="fas fa-camera mr-1"></i>Upload Photo
                                                    </label>
                                                    <input type="file" id="image" name="image" accept="image/*"
                                                        style="display: none;">
                                                    @error('image')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">
                                                <i class="fas fa-user mr-1"></i>Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}" required
                                                placeholder="Enter full name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">
                                                <i class="fas fa-envelope mr-1"></i>Email Address <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required
                                                placeholder="Enter email address">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number">
                                                <i class="fas fa-phone mr-1"></i>Phone Number
                                            </label>
                                            <input type="text"
                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                                                placeholder="Enter phone number">
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role_id">
                                                <i class="fas fa-user-tag mr-1"></i>Role <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('role_id') is-invalid @enderror"
                                                id="role_id" name="role_id" required>
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">
                                                <i class="fas fa-venus-mars mr-1"></i>Gender
                                            </label>
                                            <select class="form-control @error('gender') is-invalid @enderror"
                                                id="gender" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                    Male
                                                </option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="age">
                                                <i class="fas fa-birthday-cake mr-1"></i>Age
                                            </label>
                                            <input type="number" class="form-control @error('age') is-invalid @enderror"
                                                id="age" name="age" value="{{ old('age') }}"
                                                min="0" max="100" placeholder="Enter age">
                                            @error('age')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="address">
                                                <i class="fas fa-map-marker-alt mr-1"></i>Address
                                            </label>
                                            <input type="text"
                                                class="form-control @error('address') is-invalid @enderror" id="address"
                                                name="address" value="{{ old('address') }}" placeholder="Enter address">
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Section -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">
                                                <i class="fas fa-toggle-on mr-1"></i>Status <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="active"
                                                    {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="inactive"
                                                    {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Section -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="mb-3">
                                            <i class="fas fa-lock mr-1"></i>Account Security
                                        </h6>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">
                                                <i class="fas fa-key mr-1"></i>Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" required minlength="8"
                                                    placeholder="Enter password">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="togglePassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Password must be at least 8 characters
                                                long</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">
                                                <i class="fas fa-key mr-1"></i>Confirm Password <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" name="password_confirmation" required
                                                minlength="8" placeholder="Confirm password">
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <a href="{{ route('users/list/page') }}" class="btn btn-secondary mr-2">
                                                <i class="fas fa-times mr-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-user-plus mr-1"></i>Create User
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Image preview
            $('#image').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-image').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);

                // Toggle icon
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            // Form validation
            $('#addUserForm').submit(function(e) {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();

                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }
            });

            // Real-time password confirmation validation
            $('#password_confirmation').on('keyup', function() {
                const password = $('#password').val();
                const confirmPassword = $(this).val();

                if (confirmPassword && password !== confirmPassword) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
