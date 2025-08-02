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
                    <h3 class="page-title">Add Expense</h3>
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
                    <form method="POST" action="{{ route('form.expense.save') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Section: Basic Information --}}
                        <h5 class="mb-3 mt-2">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Expense Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('expense_type_id') is-invalid @enderror" name="expense_type_id">
                                        <option value="">Select Expense Type</option>
                                        @foreach($expenseTypes as $expenseType)
                                            <option value="{{ $expenseType->id }}" {{ old('expense_type_id') == $expenseType->id ? 'selected' : '' }}>
                                                {{ $expenseType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}" placeholder="Enter expense title">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Expense Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('expense_date') is-invalid @enderror"
                                        name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Financial Information --}}
                        <h5 class="mb-3 mt-4">Financial Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                            name="amount" value="{{ old('amount') }}" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                        </div>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status">
                                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                name="description" rows="4" placeholder="Enter expense description">{{ old('description') }}</textarea>
                        </div>

                        {{-- Section: Receipt Upload --}}
                        <h5 class="mb-3 mt-4">Receipt & Documentation</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Receipt</label>
                                    <div class="custom-file mb-3">
                                        <input type="file"
                                            class="custom-file-input @error('receipt') is-invalid @enderror" id="receipt"
                                            name="receipt" accept="image/*,application/pdf"
                                            onchange="document.getElementById('receiptLabel').innerText = this.files[0]?.name || 'Choose file'">
                                        <label class="custom-file-label" id="receiptLabel" for="receipt">Choose
                                            file</label>
                                    </div>
                                    <small class="text-muted">Supported formats: JPG, PNG, PDF (Max: 2MB)</small>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-right mt-4">
                            <a href="{{ route('form.expense.list') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Expense</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
@endsection