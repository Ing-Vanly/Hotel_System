@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Leave Request Details</h3>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Leave Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">Employee:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-plaintext">
                                                {{ $leave->employee->full_name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">Position:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-plaintext">{{ $leave->employee->position }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">Leave Type:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-plaintext">{{ $leave->leaveType->name }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">Status:</label>
                                        <div class="col-md-8">
                                            <span class="{{ $leave->status_badge }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">Start Date:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-plaintext">{{ $leave->start_date->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">End Date:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-plaintext">{{ $leave->end_date->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">Duration:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-plaintext">{{ $leave->duration }} {{ $leave->duration == 1 ? 'day' : 'days' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4">Applied On:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-plaintext">{{ $leave->created_at->format('d M Y, h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($leave->reason)
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-2">Reason:</label>
                                            <div class="col-md-10">
                                                <p class="form-control-plaintext">{{ $leave->reason }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Employee Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                @if($leave->employee->photo)
                                    <img src="{{ asset('upload/' . $leave->employee->photo) }}" alt="Employee Photo" 
                                         class="img-fluid rounded-circle mb-3" width="120" height="120">
                                @else
                                    <div class="avatar-placeholder mb-3">
                                        <span>{{ substr($leave->employee->first_name, 0, 1) }}{{ substr($leave->employee->last_name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <h5>{{ $leave->employee->full_name }}</h5>
                                <p class="text-muted">{{ $leave->employee->position }}</p>
                                <p class="text-muted">{{ $leave->employee->email }}</p>
                                <p class="text-muted">{{ $leave->employee->phone }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="btn-group-vertical btn-block">
                                @if($leave->status == 'pending')
                                    <a href="{{ route('leave.approve', $leave->id) }}" class="btn btn-success mb-2">
                                        <i class="fas fa-check"></i> Approve Leave
                                    </a>
                                    <a href="{{ route('leave.cancel', $leave->id) }}" class="btn btn-warning mb-2">
                                        <i class="fas fa-times"></i> Reject Leave
                                    </a>
                                @endif
                                
                                <a href="{{ route('leave.edit', $leave->id) }}" class="btn btn-primary mb-2">
                                    <i class="fas fa-pencil-alt"></i> Edit Leave
                                </a>
                                
                                <button type="button" class="btn btn-danger mb-2" data-toggle="modal" data-target="#delete_leave">
                                    <i class="fas fa-trash-alt"></i> Delete Leave
                                </button>
                                
                                <a href="{{ route('leave.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div id="delete_leave" class="modal fade delete-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="{{ asset('assets/img/sent.png') }}" alt="" width="50" height="46">
                    <h3 class="delete_class">Are you sure you want to delete this leave request?</h3>
                    <div class="m-t-20">
                        <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                        <form method="POST" action="{{ route('leave.destroy', $leave->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
<style>
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 36px;
        font-weight: bold;
        color: #6c757d;
        border: 2px solid #dee2e6;
    }
</style>
@endsection