@extends('layouts.master')

@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-4">Edit Leave Request</h3>
                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Request Details</h5>
                </div>
                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Employee & Leave Type --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employee <span class="text-danger">*</span></label>
                                    <select class="form-control @error('employee_id') is-invalid @enderror" name="employee_id" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }} - {{ $employee->position }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Leave Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('leave_type_id') is-invalid @enderror" name="leave_type_id" required>
                                        <option value="">Select Leave Type</option>
                                        @foreach($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType->id }}"
                                                {{ old('leave_type_id', $leave->leave_type_id) == $leaveType->id ? 'selected' : '' }}>
                                                {{ $leaveType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('leave_type_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                <option value="pending" {{ old('status', $leave->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $leave->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $leave->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Dates --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('start_date') is-invalid @enderror"
                                        name="start_date" id="start_date"
                                        value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}" required autocomplete="off">
                                    @error('start_date')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('end_date') is-invalid @enderror"
                                        name="end_date" id="end_date"
                                        value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}" required autocomplete="off">
                                    @error('end_date')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Duration (auto updated) --}}
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" id="duration" class="form-control" readonly
                                value="{{ $leave->duration }} {{ $leave->duration == 1 ? 'day' : 'days' }}">
                        </div>

                        {{-- Reason --}}
                        <div class="form-group">
                            <label>Reason</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror"
                                name="reason" rows="4" placeholder="Enter reason for leave...">{{ old('reason', $leave->reason) }}</textarea>
                            @error('reason')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="form-group mt-4 d-flex justify-content-between">
                            <a href="{{ route('leave.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Leave Request</button>
                        </div>

                    </form>
                </div>
            </div>
            {{-- End Card --}}

        </div>
    </div>
@endsection
@section('script')
<script>
    $(function() {
        $("#start_date, #end_date").datepicker({
            dateFormat: "yy-mm-dd"
        });

        function calculateDuration() {
            const startVal = $('#start_date').val();
            const endVal = $('#end_date').val();

            if (startVal && endVal) {
                const start = new Date(startVal);
                const end = new Date(endVal);
                const diffTime = end - start;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                if (diffDays > 0) {
                    $('#duration').val(diffDays + ' day' + (diffDays > 1 ? 's' : ''));
                } else {
                    $('#duration').val('');
                }
            } else {
                $('#duration').val('');
            }
        }

        $('#start_date, #end_date').on('change', calculateDuration);

        // Calculate on load for old values
        calculateDuration();
    });
</script>
@endsection
