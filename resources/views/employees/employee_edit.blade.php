@extends('layouts.master')

@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Edit Employee</h3>
                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Employee Information</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('form.employee.update', $employee->id) }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Section: Personal Info --}}
                        <h5 class="mb-3 mt-2">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" name="first_name"
                                        value="{{ old('first_name', $employee->first_name) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" name="last_name"
                                        value="{{ old('last_name', $employee->last_name) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" name="dob"
                                        value="{{ old('dob', $employee->dob) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Contact Info --}}
                        <h5 class="mb-3 mt-4">Contact Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ old('email', $employee->email) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input class="form-control" type="text" name="phone"
                                        value="{{ old('phone', $employee->phone) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>National ID</label>
                                    <input class="form-control" type="text" name="national_id"
                                        value="{{ old('national_id', $employee->national_id) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Job Details --}}
                        <h5 class="mb-3 mt-4">Job Details</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Joining Date</label>
                                    <input type="date" class="form-control" name="joining_date"
                                        value="{{ old('joining_date', $employee->joining_date) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Role / Position</label>
                                    <select class="form-control" name="position">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('position', $employee->position) == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salary</label>
                                    <input type="number" step="0.01" class="form-control" name="salary"
                                        value="{{ old('salary', $employee->salary) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Other Details --}}
                        <h5 class="mb-3 mt-4">Other Details</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender">
                                        <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="input-group" style="margin-top: -10px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                        </div>
                                        <select name="status" class="form-control">
                                            <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" name="address" rows="3">{{ old('address', $employee->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Profile Image --}}
                        <h5 class="mb-3 mt-4">Profile Image</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Photo Upload</label>
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input" id="photo" name="photo"
                                            onchange="document.getElementById('photoLabel').innerText = this.files[0]?.name || 'Choose file'">
                                        <label class="custom-file-label" for="photo" id="photoLabel">
                                            Choose file
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if ($employee->photo)
                                    <div class="card shadow-sm border rounded p-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/upload/' . $employee->photo) }}" alt="Employee Photo"
                                                class="rounded-circle mr-3"
                                                style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #ddd;">
                                            <div>
                                                <div class="font-weight-bold">{{ $employee->photo }}</div>
                                                <small class="text-muted">Current uploaded image</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">Update Employee</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
@endsection
