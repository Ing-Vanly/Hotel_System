@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Edit Customer</h3>
                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Customer Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('form/customer/update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Section: Basic Info --}}
                        <h5 class="mb-3 mt-2">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer ID</label>
                                    <input type="text" class="form-control" name="bkg_customer_id"
                                        value="{{ $customerEdit->bkg_customer_id }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $customerEdit->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" name="dob"
                                        value="{{ $customerEdit->dob }}">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Contact Info --}}
                        <h5 class="mb-3 mt-4">Contact Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email"
                                        value="{{ $customerEdit->email }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number"
                                        value="{{ $customerEdit->ph_number }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control" name="country"
                                        value="{{ $customerEdit->country }}" placeholder="Enter country">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Identification --}}
                        <h5 class="mb-3 mt-4">Identification</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender">
                                        <option value="Male" {{ $customerEdit->gender == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ $customerEdit->gender == 'Female' ? 'selected' : '' }}>
                                            Female</option>
                                        <option value="Other" {{ $customerEdit->gender == 'Other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>National ID</label>
                                    <input type="text" class="form-control" name="national_id"
                                        value="{{ $customerEdit->national_id }}"
                                        placeholder="Enter National ID or Passport Number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="input-group" style="margin-top: -10px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select name="status" class="form-control" required>
                                            <option value="1" {{ $customerEdit->status == 1 ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="0" {{ $customerEdit->status == 0 ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Section: Address --}}
                        <h5 class="mb-3 mt-4">Address & Notes</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" name="address" rows="2" placeholder="Enter customer address">{{ $customerEdit->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea class="form-control" name="message" rows="2">{{ $customerEdit->message }}</textarea>
                                </div>
                            </div>
                        </div>
                        {{-- Section: Profile Image --}}
                        <h5 class="mb-3 mt-4">Profile Image</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Upload</label>
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input" id="customFile" name="fileupload"
                                            onchange="updateFileName(this)">
                                        <input type="hidden" name="hidden_fileupload"
                                            value="{{ $customerEdit->fileupload }}">
                                        <label class="custom-file-label" for="customFile" id="customFileLabel">
                                            {{ $customerEdit->fileupload ?? 'Choose file' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow-sm border rounded p-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ URL::to('/assets/upload/' . $customerEdit->fileupload) }}"
                                            alt="Customer Image" class="rounded-circle mr-3"
                                            style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #ddd;">
                                        <div>
                                            <div class="font-weight-bold">{{ $customerEdit->fileupload }}</div>
                                            <small class="text-muted">Current uploaded image</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Submit --}}
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">Update Customer</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
@endsection
