@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-4">Add Reservation</h3>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Booking Details</h4>
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
                    <form action="{{ route('form/booking/save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer (Optional)</label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id"
                                        name="customer_id" onchange="populateCustomerData()">
                                        <option value="">Select Customer</option>
                                        @if (isset($customers) && $customers->count() > 0)
                                            @foreach ($customers as $customer)
                                                <option {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                                                    value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                    data-email="{{ $customer->email }}"
                                                    data-phone="{{ $customer->ph_number }}"
                                                    data-file="{{ $customer->fileupload }}"
                                                    data-image="{{ $customer->fileupload ? asset('assets/upload/' . $customer->fileupload) : '' }}">
                                                    {{ $customer->name }}</option>
                                            @endforeach
                                        @elseif(isset($user) && $user->count() > 0)
                                            @foreach ($user as $customer)
                                                <option {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                                                    value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                    data-email="{{ $customer->email }}"
                                                    data-phone="{{ $customer->ph_number }}"
                                                    data-file="{{ $customer->fileupload }}"
                                                    data-image="{{ $customer->fileupload ? asset('assets/upload/' . $customer->fileupload) : '' }}">
                                                    {{ $customer->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('customer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted mt-3">Leave empty for walk-in guests</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Guest Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('guest_name') is-invalid @enderror"
                                        name="guest_name" placeholder="Enter guest name" value="{{ old('guest_name') }}"
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
                                    <label>Guest Email</label>
                                    <input type="email" class="form-control @error('guest_email') is-invalid @enderror"
                                        name="guest_email" placeholder="Enter guest email"
                                        value="{{ old('guest_email') }}">
                                    @error('guest_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room</label>
                                    <select class="form-control @error('room_id') is-invalid @enderror" id="room_id"
                                        name="room_id" onchange="updateRoomRate()">
                                        <option value="">Select Available Room</option>
                                        @if (isset($rooms) && $rooms->count() > 0)
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
                                                <option {{ old('room_id') == $room->id ? 'selected' : '' }}
                                                    value="{{ $room->id }}"
                                                    data-rate="{{ $roomRate }}"
                                                    data-room-type-id="{{ $roomTypeId }}"
                                                    data-room-type-name="{{ $roomTypeName }}"
                                                    class="room-option">
                                                    {{ $room->name ?: ($room->room_number ? 'Room ' . $room->room_number : 'Room ' . $room->id) }}
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
                                    <!-- Hidden input to store room_type_id for form submission -->
                                    <input type="hidden" id="room_type_id" name="room_type_id"
                                        value="{{ old('room_type_id') }}">
                                    <!-- Display input showing room type name (read-only) -->
                                    <input type="text" class="form-control @error('room_type_id') is-invalid @enderror"
                                        id="room_type_display" name="room_type_display" placeholder="Select a room first"
                                        value="{{ old('room_type_display') }}" readonly
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
                                    <label>Total Amount</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('total_amount') is-invalid @enderror" id="total_amount"
                                        name="total_amount" value="{{ old('total_amount') }}" readonly>
                                    @error('total_amount')
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
                                        <option value="" disabled selected>Select the total number of guests</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('guest_count') == $i ? 'selected' : '' }}>{{ $i }}
                                                {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
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
                                        <input type="date"
                                            class="form-control @error('check_in_date') is-invalid @enderror"
                                            name="check_in_date" value="{{ old('check_in_date') }}"
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
                                        <input type="date"
                                            class="form-control @error('check_out_date') is-invalid @enderror"
                                            name="check_out_date" value="{{ old('check_out_date') }}"
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
                                    <label>Guest Phone</label>
                                    <input type="text" class="form-control @error('guest_phone') is-invalid @enderror"
                                        name="guest_phone"
                                        placeholder="Enter guest phone"value="{{ old('guest_phone') }}">
                                    @error('guest_phone')
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
                                            {{ old('payment_status') == 'pending' ? 'selected' : 'selected' }}>Pending
                                        </option>
                                        <option value="partial"
                                            {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>
                                            Paid</option>
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
                                            {{ old('booking_status') == 'pending' ? 'selected' : 'selected' }}>Pending
                                        </option>
                                        <option value="confirmed"
                                            {{ old('booking_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
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
                                            {{ old('booking_source') == 'walk_in' ? 'selected' : 'selected' }}>Walk-in
                                        </option>
                                        <option value="phone" {{ old('booking_source') == 'phone' ? 'selected' : '' }}>
                                            Phone</option>
                                        <option value="email" {{ old('booking_source') == 'email' ? 'selected' : '' }}>
                                            Email</option>
                                        <option value="website"
                                            {{ old('booking_source') == 'website' ? 'selected' : '' }}>Website</option>
                                        <option value="agency" {{ old('booking_source') == 'agency' ? 'selected' : '' }}>
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
                                            id="customFile" name="fileupload" value="{{ old('fileupload') }}">
                                        <label class="custom-file-label" for="customFile">Choose file</label>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Customer Image Preview -->
                                <div id="customer-image-preview" class="mt-2" style="display: none;">
                                    <img id="customer-image" src="" alt="Customer Image" class="img-thumbnail"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                    <small class="text-muted d-block">Customer Photo</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8" style="margin-left: -17px;">
                            <div class="form-group">
                                <label>Special Requests</label>
                                <textarea class="form-control @error('special_requests') is-invalid @enderror" rows="2"
                                    name="special_requests" placeholder="Any special requests or notes...">{{ old('special_requests') }}</textarea>
                                @error('special_requests')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="d-flex justify-content-end gap-2" style="margin: 20px">
                    <a href="{{ route('form/allbooking') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3"
                        style="margin-right:10px">
                        <i class="fas fa-times-circle mr-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                        <i class="fas fa-save mr-1"></i> Create Booking
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@section('script')
    <script>
        // Store all rooms data for filtering
        let allRooms = [];

        $(document).ready(function() {
            // Store all rooms data
            $('#room_id option').each(function() {
                if ($(this).val()) {
                    allRooms.push({
                        id: $(this).val(),
                        text: $(this).text(),
                        rate: $(this).data('rate'),
                        roomTypeId: $(this).data('room-type-id'),
                        roomTypeName: $(this).data('room-type-name'),
                        element: $(this).clone()
                    });
                }
            });

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

            // If there's a pre-selected room (from old input), trigger the update
            if ($('#room_id').val()) {
                updateRoomRate();
            }
        });

        // Populate customer data when customer is selected
        function populateCustomerData() {
            const selectedOption = $('#customer_id option:selected');
            const customerId = selectedOption.val();

            if (customerId) {
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
                }

                // Show customer image if available
                if (customerImage) {
                    $('#customer-image').attr('src', customerImage);
                    $('#customer-image-preview').show();
                } else {
                    $('#customer-image-preview').hide();
                }
            } else {
                // Clear fields if no customer selected
                $('input[name="guest_name"]').val('');
                $('input[name="guest_email"]').val('');
                $('input[name="guest_phone"]').val('');
                $('.custom-file-label').text('Choose file');
                $('#customer-image-preview').hide();
            }
        }

        // Note: filterRoomsByType() function removed since room type is now auto-populated and read-only

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
            } else {
                // If just room is selected but no dates, show room rate as base amount
                if (roomRate > 0) {
                    $('#total_amount').val(roomRate.toFixed(2));
                    console.log('→ No dates selected, showing room rate:', roomRate.toFixed(2));
                } else {
                    $('#total_amount').val('0.00');
                    console.log('✗ Missing data, total set to 0.00 - CheckIn:', checkIn, 'CheckOut:', checkOut, 'Rate:', roomRate);
                }
            }
            console.log('=== calculateTotal() finished ===');
        }

        // Check room availability via AJAX
        function checkRoomAvailability() {
            const checkIn = $('input[name="check_in_date"]').val();
            const checkOut = $('input[name="check_out_date"]').val();
            const roomTypeId = $('#room_type_id').val(); // Get from hidden field

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

                // Make AJAX call to check availability
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.post('{{ route('form/booking/check-availability') }}', {
                        check_in_date: checkIn,
                        check_out_date: checkOut,
                        room_type_id: roomTypeId
                    })
                    .done(function(response) {
                        updateAvailableRooms(response.available_rooms);
                    })
                    .fail(function(xhr) {
                        console.error('Failed to check room availability:', xhr.responseJSON?.error || 'Unknown error');
                        // Keep current rooms if API fails
                    });
            }
        }

        // Update room dropdown with available rooms
        function updateAvailableRooms(availableRooms) {
            const roomSelect = $('#room_id');
            const currentRoomId = roomSelect.val();

            // Clear current options except the first one
            roomSelect.find('option:not(:first)').remove();

            // Add available rooms
            availableRooms.forEach(function(room) {
                const option = $('<option></option>')
                    .attr('value', room.id)
                    .attr('data-rate', room.rate)
                    .attr('data-room-type-id', room.room_type_id)
                    .attr('data-room-type-name', room.room_type_name)
                    .addClass('room-option')
                    .text(room.name);
                roomSelect.append(option);
            });

            // Restore previous selection if still available
            if (currentRoomId && roomSelect.find('option[value="' + currentRoomId + '"]').length > 0) {
                roomSelect.val(currentRoomId);
                updateRoomRate(); // Update room type and total when restoring selection
            } else {
                roomSelect.val('');
                $('#room_type_id').val('');
                $('#room_type_display').val('');
                $('#total_amount').val('0.00');
            }

            // Show availability message
            const availabilityMessage = availableRooms.length > 0 ?
                `${availableRooms.length} room(s) available for selected dates` :
                'No rooms available for selected dates';

            // Remove existing message
            $('.availability-message').remove();
            // Add new message
            roomSelect.after(`<small class="availability-message text-info">${availabilityMessage}</small>`);
        }

        // Custom file input handling
        $(document).on('change', '.custom-file-input', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    </script>
@endsection
@endsection
