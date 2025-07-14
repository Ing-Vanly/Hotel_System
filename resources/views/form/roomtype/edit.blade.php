@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Edit Room Type</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('roomtype.update', $roomType->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('room_name') is-invalid @enderror" name="room_name" value="{{ old('room_name', $roomType->room_name) }}" required>
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
                                    <input type="number" step="0.01" min="0" class="form-control @error('base_price') is-invalid @enderror" name="base_price" value="{{ old('base_price', $roomType->base_price ?? 0) }}" required>
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
                                        <option value="" disabled>--Select--</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('max_occupancy', $roomType->max_occupancy ?? 2) == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Person' : 'Persons' }}</option>
                                        @endfor
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
                                    <textarea class="form-control @error('description') is-invalid @enderror" rows="3" name="description" placeholder="Describe this room type...">{{ old('description', $roomType->description ?? '') }}</textarea>
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
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $roomType->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Amenities</label>
                                    @php
                                        $amenitiesValue = '';
                                        if (isset($roomType->amenities)) {
                                            if (is_string($roomType->amenities)) {
                                                $amenities = json_decode($roomType->amenities, true);
                                                $amenitiesValue = is_array($amenities) ? implode(', ', $amenities) : $roomType->amenities;
                                            } else {
                                                $amenitiesValue = $roomType->amenities;
                                            }
                                        }
                                    @endphp
                                    <input type="text" class="form-control @error('amenities') is-invalid @enderror" name="amenities" value="{{ old('amenities', $amenitiesValue) }}" placeholder="WiFi, TV, AC, Balcony (separated by commas)">
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
                        <button type="submit" class="btn btn-primary buttonedit ml-2">Update Room Type</button>
                        <a href="{{ route('roomtype.index') }}" class="btn btn-secondary buttonedit">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection