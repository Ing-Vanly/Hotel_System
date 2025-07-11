@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="page-title mt-5">Edit Leave Type</h4>
                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="page-title">Update Leave Type</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('leavetype.update', $leave_type->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Section: Leave Type Info --}}
                        <h5 class="mb-3 mt-2">Leave Type Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Leave Type Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $leave_type->name) }}" placeholder="Enter leave type name"
                                        required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Max Leave Days <span class="text-danger">*</span></label>
                                    <input type="number" name="max_leave_count"
                                        class="form-control @error('max_leave_count') is-invalid @enderror"
                                        value="{{ old('max_leave_count', $leave_type->max_leave_count) }}" min="0"
                                        placeholder="Enter max leave days" required>
                                    @error('max_leave_count')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section: Description --}}
                        <h5 class="mb-3 mt-4">Description</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Details / Notes</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                        placeholder="Optional description about the leave type">{{ old('description', $leave_type->description) }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            {{-- Submit --}}
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">Update Leave Type</button>
                            <a href="{{ route('leavetype.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
@endsection
