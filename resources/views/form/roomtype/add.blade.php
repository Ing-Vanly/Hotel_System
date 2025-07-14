@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Add Room Type</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('roomtype.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('room_name') is-invalid @enderror" name="room_name" value="{{ old('room_name') }}" required>
                                    @error('room_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Base Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('base_price') is-invalid @enderror" name="base_price" value="{{ old('base_price') }}" required>
                                    @error('base_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Max Occupancy <span class="text-danger">*</span></label>
                                    <select class="form-control @error('max_occupancy') is-invalid @enderror" name="max_occupancy" required>
                                        <option value="" disabled selected>--Select--</option>
                                        <option value="1" {{ old('max_occupancy') == '1' ? 'selected' : '' }}>1 Person</option>
                                        <option value="2" {{ old('max_occupancy') == '2' ? 'selected' : '' }}>2 Persons</option>
                                        <option value="3" {{ old('max_occupancy') == '3' ? 'selected' : '' }}>3 Persons</option>
                                        <option value="4" {{ old('max_occupancy') == '4' ? 'selected' : '' }}>4 Persons</option>
                                        <option value="5" {{ old('max_occupancy') == '5' ? 'selected' : '' }}>5 Persons</option>
                                        <option value="6" {{ old('max_occupancy') == '6' ? 'selected' : '' }}>6 Persons</option>
                                    </select>
                                    @error('max_occupancy')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" rows="3" name="description" placeholder="Describe this room type...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Amenities</label>
                                    <input type="text" class="form-control @error('amenities') is-invalid @enderror" name="amenities" value="{{ old('amenities') }}" placeholder="WiFi, TV, AC, Balcony (separated by commas)">
                                    <small class="form-text text-muted">Enter amenities separated by commas (e.g., WiFi, TV, AC, Balcony)</small>
                                    @error('amenities')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary buttonedit ml-2">Save Room Type</button>
                        <a href="{{ route('roomtype.index') }}" class="btn btn-secondary buttonedit">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection