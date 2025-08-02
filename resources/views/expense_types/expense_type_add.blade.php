@extends('layouts.master')

@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        {{-- Optional Title Area --}}
                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="page-title">Add Expense Type</h3>
                </div>
                <div class="card-body">
                    {{-- Show Errors --}}
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
                    <form method="POST" action="{{ route('form.expensetype.save') }}">
                        @csrf

                        {{-- Section: Basic Information --}}
                        <h5 class="mb-3 mt-2">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expense Type Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" placeholder="Enter expense type name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                        </div>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status">
                                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Description --}}
                        <h5 class="mb-3 mt-4">Additional Details</h5>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                name="description" rows="4" placeholder="Enter expense type description">{{ old('description') }}</textarea>
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-right mt-4">
                            <a href="{{ route('form.expensetype.list') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Expense Type</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
@endsection