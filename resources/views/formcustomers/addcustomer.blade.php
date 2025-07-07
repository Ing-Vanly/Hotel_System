@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">

                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="page-title">Add Customer</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('form/addcustomer/save') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Section: Basic Info --}}
                        <h5 class="mb-3 mt-2">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" placeholder="Enter customer name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                        name="dob" value="{{ old('dob') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" name="gender">
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Prefer not
                                            to say</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Contact Info --}}
                        <h5 class="mb-3 mt-4">Contact Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                        name="phone_number" value="{{ old('phone_number') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        name="country" value="{{ old('country') }}" placeholder="Enter country">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Identification --}}
                        <h5 class="mb-3 mt-4">Identification</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>National ID</label>
                                    <input type="text" class="form-control @error('national_id') is-invalid @enderror"
                                        name="national_id" value="{{ old('national_id') }}"
                                        placeholder="Enter National ID or Passport">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2"
                                        placeholder="Enter customer address">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Other Details --}}
                        <h5 class="mb-3 mt-4">Other Details</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>File Upload</label>
                                    <div class="custom-file mb-3">
                                        <input type="file"
                                            class="custom-file-input @error('fileupload') is-invalid @enderror"
                                            id="customFile" name="fileupload"
                                            onchange="document.getElementById('customFileLabel').innerText = this.files[0]?.name || 'Choose file'">
                                        <label class="custom-file-label" for="customFile" id="customFileLabel">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea name="message" rows="2" class="form-control @error('message') is-invalid @enderror"
                                        placeholder="Optional message">{{ old('message') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4" style="margin-top:7px ">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="input-group" style="margin-top: -10px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select name="status" class="form-control" required>
                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">Create Customer</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
@endsection
