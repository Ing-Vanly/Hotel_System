@extends('layouts.master')
@section('content')
    @push('css')
        <style>
            .badge-primary:hover {
                background-color: #0056b3;
                cursor: pointer;
            }
            
            /* Status button colors */
            .bg-success-light {
                background-color: #d4edda !important;
                color: #155724 !important;
                border: 1px solid #c3e6cb;
            }
            
            .bg-info-light {
                background-color: #d1ecf1 !important;
                color: #0c5460 !important;
                border: 1px solid #bee5eb;
            }
            
            .bg-warning-light {
                background-color: #fff3cd !important;
                color: #856404 !important;
                border: 1px solid #ffeaa7;
            }
            
            .bg-danger-light {
                background-color: #f8d7da !important;
                color: #721c24 !important;
                border: 1px solid #f5c6cb;
            }
            
            .bg-secondary-light {
                background-color: #e2e3e5 !important;
                color: #383d41 !important;
                border: 1px solid #d6d8db;
            }
            
            /* Hover effects for status buttons */
            .bg-success-light:hover {
                background-color: #c3e6cb !important;
                color: #155724 !important;
            }
            
            .bg-info-light:hover {
                background-color: #bee5eb !important;
                color: #0c5460 !important;
            }
            
            .bg-warning-light:hover {
                background-color: #ffeaa7 !important;
                color: #856404 !important;
            }
            
            .bg-danger-light:hover {
                background-color: #f5c6cb !important;
                color: #721c24 !important;
            }
            
            .bg-secondary-light:hover {
                background-color: #d6d8db !important;
                color: #383d41 !important;
            }
        </style>
    @endpush
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5 d-flex justify-content-between align-items-center">
                            <h4 class="card-title">All Reservations</h4>
                            <a href="{{ route('form/booking/add') }}" class="btn btn-primary">Add Booking</a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Filter Card with Collapse Dropdown --}}
            <div class="row">
                <div class="col-lg-12" style="margin-top: -20px">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Filter Bookings</h5>
                            <button class="btn btn-sm btn-light" type="button" data-toggle="collapse"
                                data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="fas fa-filter mr-1"></i> Show Filters
                            </button>
                        </div>
                        <div class="collapse {{ request()->has('guest_name') || request()->has('booking_status') || request()->has('payment_status') ? 'show' : '' }}"
                            id="filterCollapse">
                            <div class="card-body">
                                <form id="bookingFilterForm" action="{{ route('form/allbooking') }}" method="GET">
                                    <div class="row formtype">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="guest_name">Guest Name</label>
                                                <input type="text" name="guest_name" id="guest_name" class="form-control"
                                                    value="{{ request('guest_name') }}" placeholder="Guest name"
                                                    oninput="submitFilterForm()">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="booking_status">Booking Status</label>
                                                <select name="booking_status" id="booking_status" class="form-control"
                                                    onchange="submitFilterForm()">
                                                    <option value="">All Status</option>
                                                    <option value="pending"
                                                        {{ request('booking_status') == 'pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="confirmed"
                                                        {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>
                                                        Confirmed</option>
                                                    <option value="checked_in"
                                                        {{ request('booking_status') == 'checked_in' ? 'selected' : '' }}>
                                                        Checked In</option>
                                                    <option value="checked_out"
                                                        {{ request('booking_status') == 'checked_out' ? 'selected' : '' }}>
                                                        Checked Out</option>
                                                    <option value="cancelled"
                                                        {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>
                                                        Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment_status">Payment Status</label>
                                                <select name="payment_status" id="payment_status" class="form-control"
                                                    onchange="submitFilterForm()">
                                                    <option value="">All Payment Status</option>
                                                    <option value="pending"
                                                        {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="paid"
                                                        {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid
                                                    </option>
                                                    <option value="partial"
                                                        {{ request('payment_status') == 'partial' ? 'selected' : '' }}>
                                                        Partial</option>
                                                    <option value="refunded"
                                                        {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                                        Refunded</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Bookings Table --}}
            <div class="row mt-4">
                <div class="col-sm-12" style="margin-top: -40px">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            @if ($allBookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="datatable table table-hover table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-id-card mr-1"></i>Booking ID</th>
                                                <th><i class="fas fa-user mr-1"></i>Guest</th>
                                                <th><i class="fas fa-bed mr-1"></i>Room</th>
                                                <th><i class="fas fa-users mr-1"></i>Guests</th>
                                                <th><i class="fas fa-calendar mr-1"></i>Check-in</th>
                                                <th><i class="fas fa-calendar-alt mr-1"></i>Check-out</th>
                                                <th><i class="fas fa-dollar-sign mr-1"></i>Amount</th>
                                                <th><i class="fas fa-info-circle mr-1"></i>Status</th>
                                                <th><i class="fas fa-credit-card mr-1"></i>Payment</th>
                                                <th class="text-right"><i class="fas fa-cogs mr-1"></i>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allBookings as $booking)
                                                <tr>
                                                    <td>
                                                        <strong class="text-primary">{{ $booking->bkg_id }}</strong>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-start">
                                                            @php
                                                                $customerImage = $booking->customer->fileupload ?? $booking->fileupload ?? null;
                                                            @endphp
                                                            @if ($customerImage)
                                                                <img src="{{ URL::to('/assets/upload/' . $customerImage) }}"
                                                                    alt="Guest"
                                                                    class="avatar avatar-sm mr-2 rounded-circle"
                                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <div
                                                                    class="avatar avatar-sm mr-2 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                                    {{ substr($booking->guest_name ?? ($booking->name ?? 'G'), 0, 1) }}
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <strong>{{ $booking->guest_name ?? $booking->name }}</strong>
                                                                @if ($booking->guest_email ?? $booking->email)
                                                                    <br><small class="text-muted"
                                                                        style="margin-top: 4px; display: block;">{{ $booking->guest_email ?? $booking->email }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($booking->room)
                                                            <div>
                                                                <strong
                                                                    class="text-primary">{{ $booking->room->name ?? ($booking->room->room_number ?? 'Room ' . $booking->room->id) }}</strong>
                                                                <br><small class="text-muted"
                                                                    style="margin-top: 4px; display: block;">{{ $booking->roomType->name ?? ($booking->room->roomType->name ?? ($booking->room->room_type ?? 'N/A')) }}</small>
                                                            </div>
                                                        @else
                                                            <div>
                                                                <strong class="text-muted">Room Not Assigned</strong>
                                                                <br><small class="text-muted"
                                                                    style="margin-top: 4px; display: block;">{{ $booking->roomType->name ?? ($booking->room_type ?? 'N/A') }}</small>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div>
                                                            <strong class="text-info">
                                                                {{ $booking->guest_count ?? ($booking->total_numbers ?? 1) }}
                                                            </strong>
                                                            <small class="text-muted ml-1">
                                                                {{ ($booking->guest_count ?? ($booking->total_numbers ?? 1)) == 1 ? 'Guest' : 'Guests' }}
                                                            </small>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        @php
                                                            $checkInDisplay = 'N/A';
                                                            if ($booking->check_in_date) {
                                                                $checkInDisplay = is_string($booking->check_in_date)
                                                                    ? \Carbon\Carbon::parse(
                                                                        $booking->check_in_date,
                                                                    )->format('M d, Y')
                                                                    : $booking->check_in_date->format('M d, Y');
                                                            } elseif ($booking->arrival_date) {
                                                                $checkInDisplay = \Carbon\Carbon::parse(
                                                                    $booking->arrival_date,
                                                                )->format('M d, Y');
                                                            }
                                                        @endphp
                                                        {{ $checkInDisplay }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $checkOutDisplay = 'N/A';
                                                            if ($booking->check_out_date) {
                                                                $checkOutDisplay = is_string($booking->check_out_date)
                                                                    ? \Carbon\Carbon::parse(
                                                                        $booking->check_out_date,
                                                                    )->format('M d, Y')
                                                                    : $booking->check_out_date->format('M d, Y');
                                                            } elseif ($booking->depature_date) {
                                                                $checkOutDisplay = \Carbon\Carbon::parse(
                                                                    $booking->depature_date,
                                                                )->format('M d, Y');
                                                            }
                                                        @endphp
                                                        {{ $checkOutDisplay }}
                                                    </td>
                                                    <td>
                                                        @if ($booking->total_amount)
                                                            <strong
                                                                class="text-success">${{ number_format($booking->total_amount, 2) }}</strong>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="actions">
                                                            @php $status = $booking->booking_status ?? 'pending'; @endphp
                                                            @if ($status == 'confirmed')
                                                                <a href="#" class="btn btn-sm bg-info-light mr-2">Confirmed</a>
                                                            @elseif ($status == 'checked_in')
                                                                <a href="#" class="btn btn-sm bg-success-light mr-2">Checked In</a>
                                                            @elseif ($status == 'checked_out')
                                                                <a href="#" class="btn btn-sm bg-secondary-light mr-2">Checked Out</a>
                                                            @elseif ($status == 'cancelled')
                                                                <a href="#" class="btn btn-sm bg-danger-light mr-2">Cancelled</a>
                                                            @else
                                                                <a href="#" class="btn btn-sm bg-warning-light mr-2">{{ ucfirst($status) }}</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="actions">
                                                            @php $paymentStatus = $booking->payment_status ?? 'pending'; @endphp
                                                            @if ($paymentStatus == 'paid')
                                                                <a href="#" class="btn btn-sm bg-success-light mr-2">Paid</a>
                                                            @elseif ($paymentStatus == 'partial')
                                                                <a href="#" class="btn btn-sm bg-info-light mr-2">Partial</a>
                                                            @elseif ($paymentStatus == 'refunded')
                                                                <a href="#" class="btn btn-sm bg-secondary-light mr-2">Refunded</a>
                                                            @else
                                                                <a href="#" class="btn btn-sm bg-warning-light mr-2">{{ ucfirst($paymentStatus) }}</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right shadow-sm py-2"
                                                                style="min-width: 150px; font-size: 15px;">
                                                                <a href="{{ url('form/booking/edit/' . $booking->bkg_id) }}"
                                                                    class="dropdown-item px-4 py-2 d-flex align-items-center">
                                                                    <i class="fas fa-pencil-alt mr-2"></i> Edit
                                                                </a>
                                                                <a href="javascript:void(0);"
                                                                    class="dropdown-item px-4 py-2 d-flex align-items-center"
                                                                    onclick="confirmDelete('{{ $booking->id }}', '{{ $booking->guest_name ?? $booking->name }}', '{{ $booking->fileupload }}')">
                                                                    <i class="fas fa-trash-alt mr-2"></i> Delete
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <form id="delete-form-{{ $booking->id }}"
                                                            action="{{ route('form/booking/delete') }}" method="POST"
                                                            style="display:none;">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $booking->id }}">
                                                            <input type="hidden" name="fileupload"
                                                                value="{{ $booking->fileupload }}">
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No bookings found</h5>
                                    <p class="text-muted">
                                        <a href="{{ route('form/booking/add') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-2"></i>Create your first booking
                                        </a>
                                    </p>
                                </div>
                            @endif
                            <div class="mt-3">
                                {{ $allBookings->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    // Delete Booking Confirmation
    function confirmDelete(bookingId, guestName, fileupload) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form with matching id
                document.getElementById('delete-form-' + bookingId).submit();
            }
        })
    }

    // Filter functionality
    let timeout = null;

    function submitFilterForm() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            document.getElementById('bookingFilterForm').submit();
        }, 500);
    }
</script>
