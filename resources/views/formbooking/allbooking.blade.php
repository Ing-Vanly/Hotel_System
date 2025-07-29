@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">
                                <i class="fas fa-calendar-check mr-2"></i>All Reservations
                            </h4>
                            <a href="{{ route('form/booking/add') }}" class="btn btn-primary float-right">
                                <i class="fas fa-plus mr-2"></i>Add Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list mr-2"></i>Bookings List
                                <small class="text-muted">
                                    ({{ $allBookings->total() }} total bookings, showing
                                    {{ $allBookings->firstItem() ?? 0 }} to
                                    {{ $allBookings->lastItem() ?? 0 }})
                                </small>
                            </h5>
                        </div>
                        <div class="card-body booking_card">
                            @if ($allBookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-center mb-0">
                                        <thead class="thead-light">
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
                                                        <div class="d-flex align-items-center">
                                                            @if ($booking->fileupload)
                                                                <img src="{{ URL::to('/assets/upload/' . $booking->fileupload) }}"
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
                                                                    <br><small
                                                                        class="text-muted">{{ $booking->guest_email ?? $booking->email }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($booking->room)
                                                            <span
                                                                class="badge badge-info">{{ $booking->room->display_name ?? $booking->room->name }}</span>
                                                            <br><small
                                                                class="text-muted">{{ $booking->room->room_type }}</small>
                                                        @else
                                                            <span
                                                                class="text-muted">{{ $booking->room_type ?? 'N/A' }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            {{ $booking->guest_count ?? ($booking->total_numbers ?? 1) }}
                                                            {{ ($booking->guest_count ?? ($booking->total_numbers ?? 1)) == 1 ? 'Guest' : 'Guests' }}
                                                        </span>
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
                                                        @php
                                                            $status = $booking->booking_status ?? 'pending';
                                                            $statusClass = match ($status) {
                                                                'confirmed' => 'badge-info',
                                                                'checked_in' => 'badge-success',
                                                                'checked_out' => 'badge-secondary',
                                                                'cancelled' => 'badge-danger',
                                                                default => 'badge-warning',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $paymentStatus = $booking->payment_status ?? 'pending';
                                                            $paymentClass = match ($paymentStatus) {
                                                                'paid' => 'badge-success',
                                                                'partial' => 'badge-info',
                                                                'refunded' => 'badge-secondary',
                                                                default => 'badge-warning',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge {{ $paymentClass }}">{{ ucfirst($paymentStatus) }}</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                type="button" data-toggle="dropdown">
                                                                <i class="fas fa-cog"></i> Actions
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item"
                                                                    href="{{ url('form/booking/edit/' . $booking->bkg_id) }}">
                                                                    <i class="fas fa-edit mr-2"></i> Edit
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#"
                                                                    onclick="confirmDelete('{{ $booking->id }}', '{{ $booking->guest_name ?? $booking->name }}', '{{ $booking->fileupload }}')">
                                                                    <i class="fas fa-trash mr-2"></i> Delete
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
                            <!-- Pagination Section -->
                            @if ($allBookings->hasPages())
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="pagination-info">
                                            <span class="text-muted">
                                                Showing {{ $allBookings->firstItem() }} to {{ $allBookings->lastItem() }}
                                                of
                                                {{ $allBookings->total() }} results
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pagination-wrapper float-right">
                                            {{ $allBookings->links() }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .pagination-wrapper .pagination {
            margin-bottom: 0;
        }

        .pagination-wrapper .page-link {
            border-radius: 4px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .pagination-wrapper .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination-info {
            padding: 8px 0;
            margin: 0;
        }

        .badge {
            font-size: 0.75em;
        }

        .avatar {
            width: 40px;
            height: 40px;
        }

        @media (max-width: 768px) {
            .pagination-wrapper {
                float: none !important;
                text-align: center;
                margin-top: 10px;
            }

            .pagination-info {
                text-align: center;
            }
        }
    </style>
@endsection
@section('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(bookingId, guestName, fileupload) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to delete booking for "${guestName}"? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: '#fff',
                customClass: {
                    popup: 'animated fadeInDown'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the booking.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    document.getElementById('delete-form-' + bookingId).submit();
                }
            });
        }
    </script>
@endsection
