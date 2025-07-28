@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-4">Edit Room</h3>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Room Information</h4>
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
                    <form action="{{ route('form/room/update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input class="form-control" type="hidden" name="bkg_room_id" value="{{ $roomEdit->bkg_room_id }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $roomEdit->name) }}" placeholder="Enter room name" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Number</label>
                                    <input type="text" class="form-control @error('room_number') is-invalid @enderror"
                                        name="room_number" value="{{ old('room_number', $roomEdit->room_number ?? '') }}" placeholder="e.g., 101, A-205">
                                    @error('room_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('room_type') is-invalid @enderror" name="room_type" required>
                                        <option disabled> --Select Room Type-- </option>
                                        @if (isset($roomTypes))
                                            @foreach ($roomTypes as $roomType)
                                                <option value="{{ $roomType->room_name }}"
                                                    {{ old('room_type', $roomEdit->room_type) == $roomType->room_name ? 'selected' : '' }}>
                                                    {{ $roomType->room_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('room_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Floor Number</label>
                                    <select class="form-control @error('floor_number') is-invalid @enderror"
                                        name="floor_number">
                                        <option value="" disabled>--Select Floor--</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('floor_number', $roomEdit->floor_number ?? '') == $i ? 'selected' : '' }}>Floor
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('floor_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Max Occupancy</label>
                                    <select class="form-control @error('max_occupancy') is-invalid @enderror"
                                        name="max_occupancy">
                                        <option value="" disabled>--Select--</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('max_occupancy', $roomEdit->max_occupancy ?? '') == $i ? 'selected' : '' }}>{{ $i }}
                                                {{ $i == 1 ? 'Person' : 'Persons' }}</option>
                                        @endfor
                                    </select>
                                    @error('max_occupancy')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status">
                                        <option value="available"
                                            {{ old('status', $roomEdit->status ?? 'available') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="maintenance" 
                                            {{ old('status', $roomEdit->status ?? '') == 'maintenance' ? 'selected' : '' }}>
                                            Maintenance</option>
                                        <option value="out_of_order"
                                            {{ old('status', $roomEdit->status ?? '') == 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>AC/NON-AC <span class="text-danger">*</span></label>
                                    <select class="form-control @error('ac_non_ac') is-invalid @enderror" name="ac_non_ac" required>
                                        <option disabled>--Select--</option>
                                        <option value="AC" {{ old('ac_non_ac', $roomEdit->ac_non_ac) == 'AC' ? 'selected' : '' }}>AC</option>
                                        <option value="NON-AC" {{ old('ac_non_ac', $roomEdit->ac_non_ac) == 'NON-AC' ? 'selected' : '' }}>NON-AC</option>
                                    </select>
                                    @error('ac_non_ac')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Food <span class="text-danger">*</span></label>
                                    <select class="form-control @error('food') is-invalid @enderror" name="food" required>
                                        <option disabled>--Select--</option>
                                        <option value="Free Breakfast"
                                            {{ old('food', $roomEdit->food) == 'Free Breakfast' ? 'selected' : '' }}>Free Breakfast</option>
                                        <option value="Free Lunch" {{ old('food', $roomEdit->food) == 'Free Lunch' ? 'selected' : '' }}>Free Lunch</option>
                                        <option value="Free Dinner" {{ old('food', $roomEdit->food) == 'Free Dinner' ? 'selected' : '' }}>
                                            Free Dinner</option>
                                        <option value="Free Breakfast & Dinner"
                                            {{ old('food', $roomEdit->food) == 'Free Breakfast & Dinner' ? 'selected' : '' }}>Free Breakfast & Dinner</option>
                                        <option value="Free Welcome Drink"
                                            {{ old('food', $roomEdit->food) == 'Free Welcome Drink' ? 'selected' : '' }}>Free Welcome Drink</option>
                                        <option value="No Free Food" {{ old('food', $roomEdit->food) == 'No Free Food' ? 'selected' : '' }}>
                                            No Free Food</option>
                                    </select>
                                    @error('food')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bed Count <span class="text-danger">*</span></label>
                                    <select class="form-control @error('bed_count') is-invalid @enderror" name="bed_count" required>
                                        <option disabled>--Select--</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('bed_count', $roomEdit->bed_count) == $i ? 'selected' : '' }}>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('bed_count')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Charges For cancellation <span class="text-danger">*</span></label>
                                    <select class="form-control @error('charges_for_cancellation') is-invalid @enderror"
                                        name="charges_for_cancellation" required>
                                        <option disabled>--Select--</option>
                                        <option value="0"
                                            {{ old('charges_for_cancellation', $roomEdit->charges_for_cancellation) == 0 ? 'selected' : '' }}>Free</option>
                                        <option value="5"
                                            {{ old('charges_for_cancellation', $roomEdit->charges_for_cancellation) == 5 ? 'selected' : '' }}>5% (Before 24 Hours)</option>
                                        <option value="-1"
                                            {{ old('charges_for_cancellation', $roomEdit->charges_for_cancellation) == -1 ? 'selected' : '' }}>No Cancellation Allowed</option>
                                    </select>
                                    @error('charges_for_cancellation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Rent <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('rent') is-invalid @enderror"
                                        name="rent" value="{{ old('rent', $roomEdit->rent) }}" placeholder="Enter rent amount" required>
                                    @error('rent')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text"
                                        class="form-control @error('phone_number') is-invalid @enderror"
                                        name="phone_number" value="{{ old('phone_number', $roomEdit->phone_number) }}" placeholder="Enter phone number">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>File Upload</label>
                                    <div class="custom-file mb-3">
                                        <input type="file"
                                            class="custom-file-input @error('fileupload') is-invalid @enderror"
                                            id="fileupload" name="fileupload">
                                        <input type="hidden" name="hidden_fileupload" value="{{ $roomEdit->fileupload }}">
                                        <label class="custom-file-label" for="fileupload">Choose file</label>
                                    </div>
                                    @if($roomEdit->fileupload)
                                        <div class="current-image mt-2">
                                            <p class="small text-muted">Current image:</p>
                                            <img src="{{ URL::to('/assets/upload/'.$roomEdit->fileupload) }}" alt="Current Room Image" 
                                                 class="img-thumbnail" style="width: 100px; height: 80px; object-fit: cover;">
                                        </div>
                                    @endif
                                    @error('fileupload')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" rows="3" name="message"
                                        placeholder="Add any additional notes about this room...">{{ old('message', $roomEdit->message) }}</textarea>
                                    @error('message')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4 gap-2">
                            <a href="{{ route('form/allrooms/page') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                style="margin-right:10px">
                                <i class="fas fa-times-circle mr-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="fas fa-save mr-1"></i> Update Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection