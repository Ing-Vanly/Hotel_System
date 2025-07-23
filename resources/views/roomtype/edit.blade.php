@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-4">Edit Room Type</h3>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Room Type Information</h4>
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
                    <form action="{{ route('roomtype.update', $roomType->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('room_name') is-invalid @enderror"
                                        name="room_name" placeholder="Enter room name"
                                        value="{{ old('room_name', $roomType->room_name) }}" required>
                                    @error('room_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Base Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('base_price') is-invalid @enderror" name="base_price"
                                        placeholder="Enter base price"
                                        value="{{ old('base_price', $roomType->base_price) }}" required>
                                    @error('base_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Max Occupancy <span class="text-danger">*</span></label>
                                    <select class="form-control @error('max_occupancy') is-invalid @enderror"
                                        name="max_occupancy" required>
                                        <option value="" disabled>Select</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('max_occupancy', $roomType->max_occupancy) == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'Person' : 'Persons' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('max_occupancy')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" rows="3" name="description"
                                        placeholder="Describe this room type...">{{ old('description', $roomType->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
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
                                                {{ old('status', (string) $roomType->status) == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', (string) $roomType->status) == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Amenities</label>
                                    @php
                                        $amenitiesValue = '';
                                        if (isset($roomType->amenities)) {
                                            $decoded = json_decode($roomType->amenities, true);
                                            $amenitiesValue = is_array($decoded)
                                                ? implode(', ', $decoded)
                                                : $roomType->amenities;
                                        }
                                    @endphp
                                    <input type="text" class="form-control @error('amenities') is-invalid @enderror"
                                        name="amenities" placeholder="WiFi, TV, AC, Balcony"
                                        value="{{ old('amenities', $amenitiesValue) }}">
                                    <small class="form-text text-muted">Separate multiple amenities with commas.</small>
                                    @error('amenities')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">Update Room Type</button>
                            <a href="{{ route('roomtype.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
