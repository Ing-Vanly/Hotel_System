@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">Room Types</h4>
                            <a href="{{ route('roomtype.create') }}" class="btn btn-primary float-right veiwbutton">Add Room Type</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table table-hover table-center mb-0">
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
                                        @foreach ($roomTypes as $roomType)
                                        <tr>
                                            <td>{{ $roomType->room_name }}</td>
                                            <td>{{ isset($roomType->description) ? Str::limit($roomType->description, 50) : 'N/A' }}</td>
                                            <td>${{ isset($roomType->base_price) ? number_format($roomType->base_price, 2) : '0.00' }}</td>
                                            <td>{{ $roomType->max_occupancy ?? 'N/A' }}</td>
                                            <td>
                                                @if(isset($roomType->amenities) && $roomType->amenities)
                                                    @php
                                                        $amenities = is_string($roomType->amenities) ? json_decode($roomType->amenities, true) : $roomType->amenities;
                                                    @endphp
                                                    @if(is_array($amenities))
                                                        {{ implode(', ', array_slice($amenities, 0, 3)) }}
                                                        @if(count($amenities) > 3)
                                                            <span class="text-muted">+{{ count($amenities) - 3 }} more</span>
                                                        @endif
                                                    @else
                                                        {{ $roomType->amenities }}
                                                    @endif
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($roomType->is_active))
                                                    <span class="badge {{ $roomType->is_active ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $roomType->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-success">Active</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v ellipse_color"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="{{ route('roomtype.edit', $roomType->id) }}"><i class="fas fa-pencil-alt m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item" href="#" onclick="deleteRoomType({{ $roomType->id }})"><i class="fas fa-trash-alt m-r-5"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Room Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this room type?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteRoomType(id) {
            $('#deleteForm').attr('action', '/form/roomtype/delete/' + id);
            $('#deleteModal').modal('show');
        }
    </script>
@endsection