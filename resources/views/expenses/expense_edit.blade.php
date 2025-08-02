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
                    <h3 class="page-title">Edit Expense</h3>
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
                    <form method="POST" action="{{ route('form.expense.update', $expense->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Section: Basic Information --}}
                        <h5 class="mb-3 mt-2">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Expense Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('expense_type_id') is-invalid @enderror" name="expense_type_id">
                                        <option value="">Select Expense Type</option>
                                        @foreach($expenseTypes as $expenseType)
                                            <option value="{{ $expenseType->id }}" {{ old('expense_type_id', $expense->expense_type_id) == $expenseType->id ? 'selected' : '' }}>
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
                                        name="title" value="{{ old('title', $expense->title) }}" placeholder="Enter expense title">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Expense Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('expense_date') is-invalid @enderror"
                                        name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}">
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
                                            name="amount" value="{{ old('amount', $expense->amount) }}" placeholder="0.00">
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
                                            <option value="pending" {{ old('status', $expense->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ old('status', $expense->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ old('status', $expense->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                name="description" rows="4" placeholder="Enter expense description">{{ old('description', $expense->description) }}</textarea>
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
                                    @if($expense->receipt)
                                        <div class="alert alert-info py-2">
                                            <small><strong>Current Receipt:</strong> 
                                                <a href="{{ asset('assets/upload/' . $expense->receipt) }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-external-link-alt"></i> {{ $expense->receipt }}
                                                </a>
                                            </small>
                                        </div>
                                    @endif
                                    <small class="text-muted">Supported formats: JPG, PNG, PDF (Max: 2MB)</small>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-right mt-4">
                            <a href="{{ route('form.expense.list') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Expense</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
@endsection