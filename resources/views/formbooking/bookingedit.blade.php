@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-4">Edit Reservation</h3>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Booking Information</h4>
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
                    <form action="{{ route('form/booking/update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Booking ID</label>
                                    <input class="form-control" type="text" name="bkg_id"
                                        value="{{ $bookingEdit->bkg_id }}" readonly>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror"
                                        name="customer_id" id="customer_id">
                                        <option value="">Select Customer</option>
                                        @if (isset($customers))
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    data-name="{{ $customer->name ?? $customer->customer_name }}"
                                                    data-email="{{ $customer->email }}"
                                                    data-phone="{{ $customer->ph_number }}"
                                                    data-file="{{ $customer->fileupload }}"
                                                    data-image="{{ $customer->fileupload ? asset('assets/upload/' . $customer->fileupload) : '' }}"
                                                    {{ old('customer_id', $bookingEdit->customer_id) == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name ?? $customer->customer_name }}
                                                    ({{ $customer->customer_id ?? $customer->id }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('customer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Guest Email</label>
                                    <input type="email" class="form-control @error('guest_email') is-invalid @enderror"
                                        name="guest_email"
                                        value="{{ old('guest_email', $bookingEdit->guest_email ?? $bookingEdit->email) }}">
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
                                    <input type="text" class="form-control @error('guest_phone') is-invalid @enderror"
                                        name="guest_phone"
                                        value="{{ old('guest_phone', $bookingEdit->guest_phone ?? $bookingEdit->ph_number) }}">
                                    @error('guest_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                           <div class="col-md-4">
                                <div class="form-group">
                                    <label>Guest Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('guest_name') is-invalid @enderror"
                                        name="guest_name"
                                        value="{{ old('guest_name', $bookingEdit->guest_name ?? $bookingEdit->name) }}"
                                        required>
                                    @error('guest_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room <span class="text-danger">*</span></label>
                                    <select class="form-control @error('room_id') is-invalid @enderror" name="room_id"
                                        id="room_id" required>
                                        <option value="">Select Room</option>
                                        @if (isset($rooms))
                                            @foreach ($rooms as $room)
                                                @php
                                                    // Fallback logic for room type
                                                    $roomTypeName = '';
                                                    $roomTypeId = $room->room_type_id ?? '';

                                                    if ($room->roomType && $room->roomType->room_name) {
                                                        $roomTypeName = $room->roomType->room_name;
                                                    } elseif (!empty($room->room_type)) {
                                                        $roomTypeName = $room->room_type;
                                                    } else {
                                                        $roomTypeName = 'Standard Room'; // fallback
                                                    }

                                                    // Ensure we have a rate
                                                    $roomRate = $room->rent ?? 0;
                                                @endphp
                                                <option value="{{ $room->id }}" data-rate="{{ $roomRate }}"
                                                    data-room-type-id="{{ $roomTypeId }}"
                                                    data-room-type-name="{{ $roomTypeName }}" class="room-option"
                                                    {{ old('room_id', $bookingEdit->room_id) == $room->id ? 'selected' : '' }}>
                                                    {{ $room->name ?: ($room->room_number ? 'Room ' . $room->room_number : 'Room ' . $room->id) }}
                                                    ({{ $roomTypeName }})
                                                    - ${{ $roomRate }}/night
                                                </option>
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
                                    <label>Room Type</label>
                                    @php
                                        // Get current room type name for display
                                        $currentRoomTypeName = '';
                                        $currentRoomTypeId = '';

                                        // Priority 1: From old input (validation errors)
                                        if (old('room_type_id')) {
                                            $currentRoomTypeId = old('room_type_id');
                                            if (isset($roomTypes)) {
                                                $roomType = $roomTypes->find($currentRoomTypeId);
                                                $currentRoomTypeName = $roomType ? $roomType->room_name : '';
                                            }
                                        }
                                        // Priority 2: From booking data
                                        elseif ($bookingEdit->room_type_id) {
                                            $currentRoomTypeId = $bookingEdit->room_type_id;
                                            if (isset($roomTypes)) {
                                                $roomType = $roomTypes->find($currentRoomTypeId);
                                                $currentRoomTypeName = $roomType ? $roomType->room_name : '';
                                            }
                                        }
                                        // Priority 3: From room relationship
                                        elseif (
                                            isset($bookingEdit->room) &&
                                            $bookingEdit->room &&
                                            $bookingEdit->room->room_type_id
                                        ) {
                                            $currentRoomTypeId = $bookingEdit->room->room_type_id;
                                            $currentRoomTypeName = $bookingEdit->room->roomType->room_name ?? '';
                                        }
                                        // Priority 4: From legacy room_type field
                                        elseif ($bookingEdit->room_type) {
                                            $currentRoomTypeName = $bookingEdit->room_type;
                                        }

                                        if (!$currentRoomTypeName) {
                                            $currentRoomTypeName = 'Select a room first';
                                        }
                                    @endphp
                                    <!-- Hidden input to store room_type_id for form submission -->
                                    <input type="hidden" id="room_type_id" name="room_type_id"
                                        value="{{ old('room_type_id', $currentRoomTypeId) }}">
                                    <!-- Display input showing room type name (read-only) -->
                                    <input type="text" class="form-control @error('room_type_id') is-invalid @enderror"
                                        id="room_type_display" name="room_type_display" placeholder="Select a room first"
                                        value="{{ old('room_type_display', $currentRoomTypeName) }}" readonly
                                        style="background-color: #f8f9fa; cursor: not-allowed;">
                                    @error('room_type_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Guest Count <span class="text-danger">*</span></label>
                                    <select class="form-control @error('guest_count') is-invalid @enderror"
                                        name="guest_count" required>
                                        <option disabled>Select all the Guest</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('guest_count', $bookingEdit->guest_count ?? $bookingEdit->total_numbers) == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}
                                            </option>
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
                                    <div class="input-group">
                                        @php
                                            $checkInDate = '';
                                            if ($bookingEdit->check_in_date) {
                                                $checkInDate = is_string($bookingEdit->check_in_date)
                                                    ? $bookingEdit->check_in_date
                                                    : $bookingEdit->check_in_date->format('Y-m-d');
                                            } elseif ($bookingEdit->arrival_date) {
                                                $checkInDate = is_string($bookingEdit->arrival_date)
                                                    ? $bookingEdit->arrival_date
                                                    : $bookingEdit->arrival_date->format('Y-m-d');
                                            }
                                        @endphp
                                        <input type="date"
                                            class="form-control @error('check_in_date') is-invalid @enderror"
                                            name="check_in_date" value="{{ old('check_in_date', $checkInDate) }}"
                                            onchange="calculateTotal(); checkRoomAvailability()" required>
                                        {{-- <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div> --}}
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
                                    <div class="input-group">
                                        @php
                                            $checkOutDate = '';
                                            if ($bookingEdit->check_out_date) {
                                                $checkOutDate = is_string($bookingEdit->check_out_date)
                                                    ? $bookingEdit->check_out_date
                                                    : $bookingEdit->check_out_date->format('Y-m-d');
                                            } elseif ($bookingEdit->depature_date) {
                                                $checkOutDate = is_string($bookingEdit->depature_date)
                                                    ? $bookingEdit->depature_date
                                                    : $bookingEdit->depature_date->format('Y-m-d');
                                            }
                                        @endphp
                                        <input type="date"
                                            class="form-control @error('check_out_date') is-invalid @enderror"
                                            name="check_out_date" value="{{ old('check_out_date', $checkOutDate) }}"
                                            onchange="calculateTotal(); checkRoomAvailability()" required>
                                        {{-- <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div> --}}
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
                                    <input type="number" step="0.01"
                                        class="form-control @error('total_amount') is-invalid @enderror" id="total_amount"
                                        name="total_amount" value="{{ old('total_amount', $bookingEdit->total_amount) }}"
                                        readonly>
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
                                    <select class="form-control @error('payment_status') is-invalid @enderror"
                                        name="payment_status">
                                        <option value="pending"
                                            {{ old('payment_status', $bookingEdit->payment_status ?? 'pending') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="partial"
                                            {{ old('payment_status', $bookingEdit->payment_status) == 'partial' ? 'selected' : '' }}>
                                            Partial</option>
                                        <option value="paid"
                                            {{ old('payment_status', $bookingEdit->payment_status) == 'paid' ? 'selected' : '' }}>
                                            Paid</option>
                                        <option value="refunded"
                                            {{ old('payment_status', $bookingEdit->payment_status) == 'refunded' ? 'selected' : '' }}>
                                            Refunded</option>
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
                                    <select class="form-control @error('booking_status') is-invalid @enderror"
                                        name="booking_status">
                                        <option value="pending"
                                            {{ old('booking_status', $bookingEdit->booking_status ?? 'pending') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="confirmed"
                                            {{ old('booking_status', $bookingEdit->booking_status) == 'confirmed' ? 'selected' : '' }}>
                                            Confirmed</option>
                                        <option value="checked_in"
                                            {{ old('booking_status', $bookingEdit->booking_status) == 'checked_in' ? 'selected' : '' }}>
                                            Checked In</option>
                                        <option value="checked_out"
                                            {{ old('booking_status', $bookingEdit->booking_status) == 'checked_out' ? 'selected' : '' }}>
                                            Checked Out</option>
                                        <option value="cancelled"
                                            {{ old('booking_status', $bookingEdit->booking_status) == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                        <option value="no_show"
                                            {{ old('booking_status', $bookingEdit->booking_status) == 'no_show' ? 'selected' : '' }}>
                                            No Show</option>
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
                                    <select class="form-control @error('booking_source') is-invalid @enderror"
                                        name="booking_source">
                                        <option value="walk_in"
                                            {{ old('booking_source', $bookingEdit->booking_source ?? 'walk_in') == 'walk_in' ? 'selected' : '' }}>
                                            Walk-in</option>
                                        <option value="phone"
                                            {{ old('booking_source', $bookingEdit->booking_source) == 'phone' ? 'selected' : '' }}>
                                            Phone</option>
                                        <option value="email"
                                            {{ old('booking_source', $bookingEdit->booking_source) == 'email' ? 'selected' : '' }}>
                                            Email</option>
                                        <option value="website"
                                            {{ old('booking_source', $bookingEdit->booking_source) == 'website' ? 'selected' : '' }}>
                                            Website</option>
                                        <option value="agency"
                                            {{ old('booking_source', $bookingEdit->booking_source) == 'agency' ? 'selected' : '' }}>
                                            Travel Agency</option>
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
                                        <input type="file"
                                            class="custom-file-input @error('fileupload') is-invalid @enderror"
                                            id="fileupload" name="fileupload">
                                        <input type="hidden" name="hidden_fileupload"
                                            value="{{ $bookingEdit->customer->fileupload ?? $bookingEdit->fileupload }}">
                                        <label class="custom-file-label" for="fileupload">Choose file</label>
                                    </div>
                                    @php
                                        $currentImage = $bookingEdit->customer->fileupload ?? $bookingEdit->fileupload ?? null;
                                    @endphp
                                    @if ($currentImage)
                                        <div class="current-image mt-2">
                                            <p class="small text-muted">Current image:</p>
                                            <img src="{{ URL::to('/assets/upload/' . $currentImage) }}"
                                                alt="Current Booking Image" class="img-thumbnail"
                                                style="width: 100px; height: 80px; object-fit: cover;">
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
                                    <label>Special Requests</label>
                                    <textarea class="form-control @error('special_requests') is-invalid @enderror" rows="3"
                                        name="special_requests" placeholder="Any special requests or notes...">{{ old('special_requests', $bookingEdit->special_requests ?? $bookingEdit->message) }}</textarea>
                                    @error('special_requests')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2" style="margin: 20px">
                            <a href="{{ route('form/allbooking') }}"
                                class="btn btn-outline-danger btn-sm rounded-pill px-3" style="margin-right:10px">
                                <i class="fas fa-times-circle mr-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="fas fa-save mr-1"></i> Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@section('script')
    <script>
        $(document).ready(function() {
            const today = new Date().toISOString().split('T')[0];
            $('input[name="check_in_date"]').attr('min', today);
            $('input[name="check_out_date"]').attr('min', today);

            // Update check-out minimum when check-in changes
            $('input[name="check_in_date"]').on('change', function() {
                const checkInDate = $(this).val();
                $('input[name="check_out_date"]').attr('min', checkInDate);
                calculateTotal();
                checkRoomAvailability();
            });

            $('input[name="check_out_date"]').on('change', function() {
                calculateTotal();
                checkRoomAvailability();
            });

            $('#room_id').on('change', updateRoomRate);
            $('#customer_id').on('change', updateCustomerInfo);

            // If there's a pre-selected room (from old input), trigger the update
            if ($('#room_id').val()) {
                updateRoomRate();
            }

            // Calculate total on page load
            calculateTotal();
        });

        // Update customer information when customer is selected
        function updateCustomerInfo() {
            const selectedOption = $('#customer_id option:selected');
            const customerId = selectedOption.val();

            if (customerId) {
                // Get customer data from option attributes
                const customerName = selectedOption.data('name') || '';
                const customerEmail = selectedOption.data('email') || '';
                const customerPhone = selectedOption.data('phone') || '';
                const customerFile = selectedOption.data('file') || '';
                const customerImage = selectedOption.data('image') || '';

                // Auto-fill guest information
                $('input[name="guest_name"]').val(customerName);
                $('input[name="guest_email"]').val(customerEmail);
                $('input[name="guest_phone"]').val(customerPhone);

                // Update file upload label if customer has a file
                if (customerFile) {
                    $('.custom-file-label').text(customerFile);
                    // Update hidden field to preserve current image
                    $('input[name="hidden_fileupload"]').val(customerFile);
                }

                // Update current image display
                if (customerImage) {
                    const currentImageHtml = `
                        <div class="current-image mt-2">
                            <p class="small text-muted">Current image:</p>
                            <img src="${customerImage}" alt="Current Booking Image" class="img-thumbnail" style="width: 100px; height: 80px; object-fit: cover;">
                        </div>
                    `;
                    $('.current-image').remove();
                    $('.custom-file').after(currentImageHtml);
                } else {
                    $('.current-image').remove();
                }
            } else {
                // Clear fields if no customer selected
                $('input[name="guest_name"]').val('');
                $('input[name="guest_email"]').val('');
                $('input[name="guest_phone"]').val('');
                $('.custom-file-label').text('Choose file');
                $('input[name="hidden_fileupload"]').val('');
                $('.current-image').remove();
            }
        }

        // Update room rate and auto-fill room type when room is selected
        function updateRoomRate() {
            const selectedOption = $('#room_id option:selected');
            const roomId = selectedOption.val();

            console.log('=== updateRoomRate() called ===');
            console.log('Selected room ID:', roomId);
            console.log('Selected option element:', selectedOption[0]);

            if (roomId) {
                // Get all data attributes
                const roomTypeId = selectedOption.data('room-type-id');
                const roomTypeName = selectedOption.data('room-type-name');
                const roomRate = parseFloat(selectedOption.data('rate')) || 0;

                console.log('Raw data attributes:', {
                    'room-type-id': selectedOption.attr('data-room-type-id'),
                    'room-type-name': selectedOption.attr('data-room-type-name'),
                    'rate': selectedOption.attr('data-rate')
                });

                console.log('Parsed data:', {
                    roomTypeId: roomTypeId,
                    roomTypeName: roomTypeName,
                    roomRate: roomRate
                });

                // Auto-fill room type ID (hidden field) and display name
                if (roomTypeName) {
                    $('#room_type_id').val(roomTypeId || '');
                    $('#room_type_display').val(roomTypeName);
                    console.log('✓ Auto-filled room type:', roomTypeName, 'ID:', roomTypeId || 'No ID');
                } else {
                    // If no room type name, provide a fallback
                    $('#room_type_id').val('');
                    $('#room_type_display').val('Standard Room');
                    console.log('✗ No room type name found, using fallback: Standard Room');
                }

                // Show visual feedback
                console.log('Room selected:', selectedOption.text().trim(), 'Type:', roomTypeName, 'Rate:', roomRate);
            } else {
                console.log('No room selected - clearing fields');
                // Clear room type when no room is selected
                $('#room_type_id').val('');
                $('#room_type_display').val('');
            }

            // Always recalculate total when room changes
            calculateTotal();
            console.log('=== updateRoomRate() finished ===');
        }

        // Calculate total amount
        function calculateTotal() {
            const checkIn = $('input[name="check_in_date"]').val();
            const checkOut = $('input[name="check_out_date"]').val();
            const selectedRoom = $('#room_id option:selected');
            const roomRate = parseFloat(selectedRoom.data('rate')) || 0;

            console.log('=== calculateTotal() called ===');
            console.log('Calculating total - CheckIn:', checkIn, 'CheckOut:', checkOut, 'Rate:', roomRate);

            if (checkIn && checkOut && roomRate > 0) {
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
                const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));

                console.log('Nights calculated:', nights);

                if (nights > 0) {
                    const total = roomRate * nights;
                    $('#total_amount').val(total.toFixed(2));
                    console.log('✓ Total amount set to:', total.toFixed(2));
                } else {
                    $('#total_amount').val('0.00');
                    console.log('✗ Invalid nights, total set to 0.00');
                }
            } else if (checkIn && checkOut && roomRate === 0) {
                // Dates are set but no room rate - keep current total or set to 0
                console.log('→ Dates set but no room rate, keeping current total');
            } else if (roomRate > 0 && (!checkIn || !checkOut)) {
                // Room is selected but missing dates - calculate based on 1 night as preview
                const total = roomRate * 1;
                $('#total_amount').val(total.toFixed(2));
                console.log('→ Room selected but missing dates, showing 1 night rate:', total.toFixed(2));
            } else {
                // Missing essential data - don't change the current value
                console.log('→ Missing essential data, keeping current total - CheckIn:', checkIn, 'CheckOut:', checkOut,
                    'Rate:', roomRate);
            }
            console.log('=== calculateTotal() finished ===');
        }

        // Check room availability via AJAX
        function checkRoomAvailability() {
            const checkIn = $('input[name="check_in_date"]').val();
            const checkOut = $('input[name="check_out_date"]').val();
            const roomTypeId = $('#room_type_id').val(); // Get from hidden field
            const currentBookingId = '{{ $bookingEdit->bkg_id }}';

            if (checkIn && checkOut) {
                // Validate dates first
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);

                if (checkOutDate <= checkInDate) {
                    alert('Check-out date must be after check-in date');
                    $('input[name="check_out_date"]').val('');
                    $('#total_amount').val('0.00');
                    return;
                }

                // For edit, we can implement availability check excluding current booking
                // For now, just recalculate total
                calculateTotal();
            }
        }

        // Custom file input handling
        $(document).on('change', '.custom-file-input', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    </script>
@endsection
@endsection
