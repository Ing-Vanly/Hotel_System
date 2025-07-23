@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    @php use Illuminate\Support\Str; @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mt-5">Room Types</h4>
                        <a href="{{ route('roomtype.create') }}" class="btn btn-primary float-right">Add Room Type</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-striped table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Room Name</th>
                                            <th>Description</th>
                                            <th>Base Price</th>
                                            <th>Max Occupancy</th>
                                            <th>Amenities</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($roomTypes as $roomType)
                                            <tr>
                                                <td>{{ $roomType->room_name }}</td>
                                                <td>{{ Str::limit($roomType->description ?? 'N/A', 50) }}</td>
                                                <td>${{ number_format($roomType->base_price ?? 0, 2) }}</td>
                                                <td>{{ $roomType->max_occupancy ?? 'N/A' }}</td>
                                                <td>
                                                    @php
                                                        $amenities = is_string($roomType->amenities)
                                                            ? json_decode($roomType->amenities, true)
                                                            : $roomType->amenities ?? [];
                                                    @endphp
                                                    @if (!empty($amenities) && is_array($amenities))
                                                        {{ implode(', ', array_slice($amenities, 0, 3)) }}
                                                        @if (count($amenities) > 3)
                                                            <span class="text-muted">+{{ count($amenities) - 3 }}
                                                                more</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">None</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $status = strtolower($roomType->status);
                                                    @endphp
                                                    <span
                                                        class="btn btn-sm
                                                        {{ $status === 'active'
                                                            ? 'bg-success-light'
                                                            : ($status === 'inactive'
                                                                ? 'bg-warning-light'
                                                                : 'bg-secondary-light') }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item"
                                                                href="{{ route('roomtype.edit', $roomType->id) }}">
                                                                <i class="fas fa-pencil-alt mr-1"></i> Edit
                                                            </a>
                                                            <form id="delete-form-{{ $roomType->id }}"
                                                                action="{{ route('roomtype.destroy', $roomType->id) }}"
                                                                method="POST" style="display:none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <a href="javascript:void(0);" class="dropdown-item"
                                                                onclick="confirmDelete({{ $roomType->id }})">
                                                                <i class="fas fa-trash-alt mr-1"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No room types found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function confirmDelete(roomTypeId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + roomTypeId).submit();
                    }
                });
            }
        </script>
    </div>
@endsection
