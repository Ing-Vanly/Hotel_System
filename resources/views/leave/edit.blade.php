@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Edit Leave Request</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employee <span class="text-danger">*</span></label>
                                    <select class="form-control @error('employee_id') is-invalid @enderror" name="employee_id" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" 
                                                {{ (old('employee_id', $leave->employee_id) == $employee->id) ? 'selected' : '' }}>
                                                {{ $employee->full_name }} - {{ $employee->position }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Leave Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('leave_type_id') is-invalid @enderror" name="leave_type_id" required>
                                        <option value="">Select Leave Type</option>
                                        @foreach($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType->id }}" 
                                                {{ (old('leave_type_id', $leave->leave_type_id) == $leaveType->id) ? 'selected' : '' }}>
                                                {{ $leaveType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('leave_type_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="pending" {{ (old('status', $leave->status) == 'pending') ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ (old('status', $leave->status) == 'approved') ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ (old('status', $leave->status) == 'rejected') ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                               name="start_date" value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}" required>
                                    </div>
                                    @error('start_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                               name="end_date" value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}" required>
                                    </div>
                                    @error('end_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Current Duration</label>
                                    <input type="text" class="form-control" value="{{ $leave->duration }} {{ $leave->duration == 1 ? 'day' : 'days' }}" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" 
                                              name="reason" rows="4" placeholder="Enter reason for leave...">{{ old('reason', $leave->reason) }}</textarea>
                                    @error('reason')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Update Leave Request</button>
                                    <a href="{{ route('leave.index') }}" class="btn btn-secondary btn-block">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Auto-calculate duration when dates are selected
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.querySelector('input[name="start_date"]');
        const endDate = document.querySelector('input[name="end_date"]');
        
        function calculateDuration() {
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                const timeDiff = end.getTime() - start.getTime();
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                
                if (daysDiff > 0) {
                    console.log('Duration: ' + daysDiff + ' days');
                }
            }
        }
        
        startDate.addEventListener('change', calculateDuration);
        endDate.addEventListener('change', calculateDuration);
    });
</script>
@endsection