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
                            <h4 class="card-title float-left mt-2">Leave Management</h4>
                            <a href="{{ route('leave.create') }}" class="btn btn-primary float-right veiwbutton">Add Leave</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Leave Type</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Reason</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($leaves as $leave)
                                            <tr>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        @if ($leave->employee->photo)
                                                            <a href="#" class="avatar">
                                                                <img alt=""
                                                                    src="{{ asset('assets/upload/' . $leave->employee->photo) }}"
                                                                    class="img-fluid">
                                                            </a>
                                                        @endif
                                                        <a href="#">{{ $leave->employee->full_name }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $leave->leaveType->name }}</td>
                                                <td>{{ $leave->start_date->format('d M Y') }}</td>
                                                <td>{{ $leave->end_date->format('d M Y') }}</td>
                                                <td>{{ $leave->duration }} {{ $leave->duration == 1 ? 'day' : 'days' }}</td>
                                                <td>
                                                    @if ($leave->status == 'approved')
                                                        <a href="#"
                                                            class="btn btn-sm bg-success-light mr-2">Approved</a>
                                                    @elseif ($leave->status == 'pending')
                                                        <a href="#"
                                                            class="btn btn-sm bg-warning-light mr-2">Pending</a>
                                                    @elseif ($leave->status == 'rejected')
                                                        <a href="#"
                                                            class="btn btn-sm bg-danger-light mr-2">Rejected</a>
                                                    @else
                                                        <a href="#"
                                                            class="btn btn-sm bg-secondary-light mr-2">{{ ucfirst($leave->status) }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($leave->reason, 30) }}</td>
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @if ($leave->status == 'pending')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('leave.approve', $leave->id) }}">
                                                                    <i class="fas fa-check m-r-5"></i> Approve
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('leave.cancel', $leave->id) }}">
                                                                    <i class="fas fa-times m-r-5"></i> Reject
                                                                </a>
                                                            @endif
                                                            <a class="dropdown-item"
                                                                href="{{ route('leave.show', $leave->id) }}">
                                                                <i class="fas fa-eye m-r-5"></i> View
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('leave.edit', $leave->id) }}">
                                                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                            </a>
                                                            <form id="delete-form-{{ $leave->id }}"
                                                                action="{{ route('leave.destroy', $leave->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <a href="javascript:void(0);" class="dropdown-item"
                                                                onclick="confirmDelete({{ $leave->id }})">
                                                                <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No leave requests found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            @if ($leaves->hasPages())
                                <div class="d-flex justify-content-center">
                                    {{ $leaves->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    // Delete Employee Confirmation
   function confirmDelete(leaveId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This leave request will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + leaveId).submit();
            }
        });
    }
</script>
