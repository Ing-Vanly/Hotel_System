@extends('layouts.master')

@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        {{-- Optional Title Area --}}
                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="page-title">Add Employee</h3>
                </div>
                <div class="card-body">
                    {{-- Show Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('form.employee.save') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Section: Personal Info --}}
                        <h5 class="mb-3 mt-2">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                        name="dob" value="{{ old('dob') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Contact Info --}}
                        <h5 class="mb-3 mt-4">Contact Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Job Info --}}
                        <h5 class="mb-3 mt-4">Job Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Joining Date</label>
                                    <input type="date" class="form-control @error('joining_date') is-invalid @enderror"
                                        name="joining_date" value="{{ old('joining_date') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role / Position</label>
                                    <select class="form-control @error('position') is-invalid @enderror" name="position">
                                        <option value="">Select</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('position') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Additional Info --}}
                        <h5 class="mb-3 mt-4">Additional Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>National ID</label>
                                    <input type="text" class="form-control @error('national_id') is-invalid @enderror"
                                        name="national_id" value="{{ old('national_id') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salary</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('salary') is-invalid @enderror"
                                        name="salary" value="{{ old('salary') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" name="gender">
                                        <option value="">Select</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Address --}}
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address') }}</textarea>
                        </div>

                        {{-- Section: Upload & Status --}}
                        <h5 class="mb-3 mt-4">Upload & Status</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Photo</label>
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input @error('photo') is-invalid @enderror"
                                            id="photo" name="photo"
                                            onchange="document.getElementById('photoLabel').innerText = this.files[0]?.name || 'Choose file'">
                                        <label class="custom-file-label" id="photoLabel" for="photo">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:7px;">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="input-group" style="margin-top: -10px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                        </div>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="active"
                                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                            <option value="terminated"
                                                {{ old('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">Create Employee</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
@endsection
