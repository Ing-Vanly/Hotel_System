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
                            <h4 class="card-title float-left mt-2">All Rooms</h4>
                            <a href="{{ route('form/addroom/page') }}" class="btn btn-primary float-right veiwbutton">Add Room</a>
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
                                            <th>Booking ID</th>
                                            <th>Name</th>
                                            <th>Room Type</th>
                                            <th>AC/NON-AC</th>
                                            <th>Food</th>
                                            <th>Bed Count</th>
                                            <th>Charges For cancellation</th>
                                            <th>Rent</th>
                                            <th>Ph.Number</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allRooms as $rooms )
                                        <tr>
                                            <td hidden class="id">{{ $rooms->id }}</td>
                                            <td hidden class="fileupload">{{ $rooms->fileupload }}</td>
                                            <td>{{ $rooms->bkg_room_id }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                <a href="profile.html" class="avatar avatar-sm mr-2">
                                                    <img class="avatar-img rounded-circle" src="{{ URL::to('/assets/upload/'.$rooms->fileupload) }}" alt="{{ $rooms->fileupload }}">
                                                </a>
                                                <a href="profile.html">{{ $rooms->name }}<span>{{ $rooms->bkg_room_id }}</span></a>
                                                </h2>
                                            </td>
                                            <td>{{ $rooms->room_type }}</td>
                                            <td>{{ $rooms->ac_non_ac }}</td>
                                            <td>{{ $rooms->food }}</td>
                                            <td>{{ $rooms->bed_count }}</td>
                                            <td>
                                                @if($rooms->charges_for_cancellation == 0)
                                                    Free
                                                @elseif($rooms->charges_for_cancellation == -1)
                                                    No Cancellation
                                                @else
                                                    {{ $rooms->charges_for_cancellation }}%
                                                @endif
                                            </td>
                                            <td>${{ number_format($rooms->rent, 2) }}</td>
                                            <td>{{ $rooms->phone_number }}</td>
                                            <td>
                                                <div class="actions"> <a href="#" class="btn btn-sm bg-success-light mr-2">Active</a> </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="{{ url('form/room/edit/'.$rooms->bkg_room_id) }}">
                                                            <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item" href="#" onclick="confirmDelete('{{ $rooms->id }}', '{{ $rooms->name }}', '{{ $rooms->fileupload }}')">
                                                            <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                        </a>
                                                        <form id="delete-form-{{ $rooms->id }}"
                                                            action="{{ route('form/room/delete') }}"
                                                            method="POST" style="display:none;">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $rooms->id }}">
                                                            <input type="hidden" name="fileupload" value="{{ $rooms->fileupload }}">
                                                        </form>
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
    @section('script')
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            function confirmDelete(roomId, roomName, fileupload) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You want to delete room "${roomName}"? This action cannot be undone!`,
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
                            text: 'Please wait while we delete the room.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        document.getElementById('delete-form-' + roomId).submit();
                    }
                });
            }
        </script>
    @endsection
@endsection
