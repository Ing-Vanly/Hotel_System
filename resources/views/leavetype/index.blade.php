@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">Leave Types</h4>
                            <a href="{{ route('leavetype.create') }}" class="btn btn-primary float-right veiwbutton">Add Leave
                                Type</a>
                        </div>
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Table Section --}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Leave Type</th>
                                            <th>Description</th>
                                            <th>Max Days</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveTypes as $type)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $type->name }}</td>
                                                <td>{{ $type->description }}</td>
                                                <td>{{ $type->max_leave_count }}</td>
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item"
                                                                href="{{ route('leavetype.edit', $type->id) }}">
                                                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                            </a>
                                                            <a class="dropdown-item deleteLeaveType" href="#"
                                                                data-toggle="modal" data-target="#delete_leave_type_modal">
                                                                <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>

                                                    {{-- Hidden ID for Delete --}}
                                                    <input type="hidden" class="leave_type_id" value="{{ $type->id }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- /.table-responsive -->
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div id="delete_leave_type_modal" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <form action="{{ route('leavetype.destroy', $type->id) }}" method="POST" id="deleteLeaveTypeForm">
                            @csrf
                            @method('DELETE')
                            <img src="{{ URL::to('assets/img/sent.png') }}" alt="" width="50" height="46">
                            <h3>Are you sure you want to delete this Leave Type?</h3>
                            <div class="mt-3">
                                <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete JS --}}
        @push('js')
            <script>
                $(document).on('click', '.deleteLeaveType', function() {
                    var leaveTypeId = $(this).closest('tr').find('.leave_type_id').val();
                    var action = '{{ route('leavetype.destroy', ':id') }}';
                    action = action.replace(':id', leaveTypeId);
                    $('#deleteLeaveTypeForm').attr('action', action);
                });
            </script>
        @endpush
    </div>
@endsection
