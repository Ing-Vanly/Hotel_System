@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-4">Add Room Type</h3>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Room Type Details</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('roomtype.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('room_name') is-invalid @enderror"
                                        name="room_name" placeholder="Enter room name" value="{{ old('room_name') }}"
                                        required>
                                    @error('room_name')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Base Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('base_price') is-invalid @enderror" name="base_price"
                                        placeholder="Enter base price" value="{{ old('base_price') }}" required>
                                    @error('base_price')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Max Occupancy <span class="text-danger">*</span></label>
                                    <select class="form-control @error('max_occupancy') is-invalid @enderror"
                                        name="max_occupancy" required>
                                        <option value="" disabled selected>Select max occupancy</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('max_occupancy') == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'Person' : 'Persons' }}</option>
                                        @endfor
                                    </select>
                                    @error('max_occupancy')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" rows="3" name="description"
                                        placeholder="Describe this room type...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                        </select>
                                    </div>
                                    @error('status')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Amenities</label>
                                    <input type="text" class="form-control @error('amenities') is-invalid @enderror"
                                        name="amenities" value="{{ old('amenities') }}"
                                        placeholder="e.g., WiFi, TV, AC, Balcony">
                                    <small class="form-text text-muted">Separate multiple amenities with commas.</small>
                                    @error('amenities')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4 gap-2">
                            <a href="{{ route('roomtype.index') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                style="margin-right:10px">
                                <i class="fas fa-times-circle mr-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="fas fa-save mr-1"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
