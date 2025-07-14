@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Add Booking</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('form/booking/save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer (Optional)</label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id">
                                        <option value=""> --Select Customer (Optional)-- </option>
                                        @if(isset($customers) && $customers->count() > 0)
                                            @foreach ($customers as $customer)
                                            <option {{ old('customer_id') == $customer->id ? "selected" : "" }} value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        @elseif(isset($user) && $user->count() > 0)
                                            @foreach ($user as $customer)
                                            <option {{ old('customer_id') == $customer->id ? "selected" : "" }} value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('customer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty for walk-in guests</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Guest Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('guest_name') is-invalid @enderror" name="guest_name" value="{{ old('guest_name') }}" required>
                                    @error('guest_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Guest Email</label>
                                    <input type="email" class="form-control @error('guest_email') is-invalid @enderror" name="guest_email" value="{{ old('guest_email') }}">
                                    @error('guest_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Guest Phone</label>
                                    <input type="text" class="form-control @error('guest_phone') is-invalid @enderror" name="guest_phone" value="{{ old('guest_phone') }}">
                                    @error('guest_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Type</label>
                                    <select class="form-control @error('room_type_id') is-invalid @enderror" id="room_type_id" name="room_type_id" onchange="loadAvailableRooms()">
                                        <option value=""> --Select Room Type-- </option>
                                        @if(isset($roomTypes) && $roomTypes->count() > 0)
                                            @foreach ($roomTypes as $roomType)
                                            <option {{ old('room_type_id') == $roomType->id ? "selected" : "" }} value="{{ $roomType->id }}" data-price="{{ $roomType->base_price ?? 0 }}">{{ $roomType->room_name }}</option>
                                            @endforeach
                                        @elseif(isset($data) && $data->count() > 0)
                                            @foreach ($data as $roomType)
                                            <option {{ old('room_type_id') == $roomType->id ? "selected" : "" }} value="{{ $roomType->id }}" data-price="{{ $roomType->base_price ?? 0 }}">{{ $roomType->room_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('room_type_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room</label>
                                    <select class="form-control @error('room_id') is-invalid @enderror" id="room_id" name="room_id" onchange="updateRoomRate()">
                                        <option value=""> --Select Room-- </option>
                                        @if(isset($rooms) && $rooms->count() > 0)
                                            @foreach ($rooms as $room)
                                            <option {{ old('room_id') == $room->id ? "selected" : "" }} value="{{ $room->id }}" data-rate="{{ $room->rent }}">{{ $room->display_name ?? $room->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('room_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Guest Count <span class="text-danger">*</span></label>
                                    <select class="form-control @error('guest_count') is-invalid @enderror" name="guest_count" required>
                                        <option value="" disabled selected>--Select--</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('guest_count') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                        @endfor
                                    </select>
                                    @error('guest_count')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Check-in Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input type="date" class="form-control @error('check_in_date') is-invalid @enderror" name="check_in_date" value="{{ old('check_in_date') }}" onchange="calculateTotal()" required>
                                    </div>
                                    @error('check_in_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Check-out Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input type="date" class="form-control @error('check_out_date') is-invalid @enderror" name="check_out_date" value="{{ old('check_out_date') }}" onchange="calculateTotal()" required>
                                    </div>
                                    @error('check_out_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total Amount</label>
                                    <input type="number" step="0.01" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" readonly>
                                    @error('total_amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Payment Status</label>
                                    <select class="form-control @error('payment_status') is-invalid @enderror" name="payment_status">
                                        <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : 'selected' }}>Pending</option>
                                        <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                    @error('payment_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Booking Status</label>
                                    <select class="form-control @error('booking_status') is-invalid @enderror" name="booking_status">
                                        <option value="pending" {{ old('booking_status') == 'pending' ? 'selected' : 'selected' }}>Pending</option>
                                        <option value="confirmed" {{ old('booking_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    </select>
                                    @error('booking_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Booking Source</label>
                                    <select class="form-control @error('booking_source') is-invalid @enderror" name="booking_source">
                                        <option value="walk_in" {{ old('booking_source') == 'walk_in' ? 'selected' : 'selected' }}>Walk-in</option>
                                        <option value="phone" {{ old('booking_source') == 'phone' ? 'selected' : '' }}>Phone</option>
                                        <option value="email" {{ old('booking_source') == 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="website" {{ old('booking_source') == 'website' ? 'selected' : '' }}>Website</option>
                                        <option value="agency" {{ old('booking_source') == 'agency' ? 'selected' : '' }}>Travel Agency</option>
                                    </select>
                                    @error('booking_source')
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
                                        <input type="file" class="custom-file-input @error('fileupload') is-invalid @enderror" id="customFile" name="fileupload" value="{{ old('fileupload') }}">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Special Requests</label>
                                    <textarea class="form-control @error('special_requests') is-invalid @enderror" rows="2" name="special_requests" placeholder="Any special requests or notes...">{{ old('special_requests') }}</textarea>
                                    @error('special_requests')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary buttonedit1">Create Booking</button>
            </form>
        </div>
    </div>
    @section('script')
    <script>
        // Load available rooms based on room type and dates
        function loadAvailableRooms() {
            const roomTypeId = $('#room_type_id').val();
            const checkIn = $('input[name="check_in_date"]').val();
            const checkOut = $('input[name="check_out_date"]').val();
            
            if (!roomTypeId || !checkIn || !checkOut) {
                return;
            }
            
            $.ajax({
                url: '{{ route("roomtype.available-rooms") }}',
                method: 'POST',
                data: {
                    room_type_id: roomTypeId,
                    check_in: checkIn,
                    check_out: checkOut,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        let roomSelect = $('#room_id');
                        roomSelect.empty();
                        roomSelect.append('<option selected disabled>--Select Room--</option>');
                        
                        response.rooms.forEach(function(room) {
                            roomSelect.append(`<option value="${room.id}" data-rate="${room.rent}">${room.display_name || room.name}</option>`);
                        });
                    }
                },
                error: function() {
                    toastr.error('Error loading available rooms');
                }
            });
        }
        
        // Update room rate when room is selected
        function updateRoomRate() {
            calculateTotal();
        }
        
        // Calculate total amount
        function calculateTotal() {
            const checkIn = $('input[name="check_in_date"]').val();
            const checkOut = $('input[name="check_out_date"]').val();
            const selectedRoom = $('#room_id option:selected');
            const roomRate = parseFloat(selectedRoom.data('rate')) || 0;
            
            if (checkIn && checkOut && roomRate > 0) {
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
                const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (nights > 0) {
                    const total = roomRate * nights;
                    $('#total_amount').val(total.toFixed(2));
                } else {
                    $('#total_amount').val('0.00');
                }
            } else {
                $('#total_amount').val('0.00');
            }
        }
        
        // Set minimum date to today
        $(document).ready(function() {
            const today = new Date().toISOString().split('T')[0];
            $('input[name="check_in_date"]').attr('min', today);
            $('input[name="check_out_date"]').attr('min', today);
            
            // Update check-out minimum when check-in changes
            $('input[name="check_in_date"]').on('change', function() {
                const checkInDate = $(this).val();
                $('input[name="check_out_date"]').attr('min', checkInDate);
                loadAvailableRooms();
                calculateTotal();
            });
            
            $('input[name="check_out_date"]').on('change', function() {
                loadAvailableRooms();
                calculateTotal();
            });
            
            $('#room_type_id').on('change', loadAvailableRooms);
            $('#room_id').on('change', updateRoomRate);
        });
    </script>
    @endsection
    
@endsection