@extends('layouts.master')

@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            {{-- Page Header --}}
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5 d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Expenses</h4>
                            <a href="{{ route('form.expense.add') }}" class="btn btn-primary">Add Expense</a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Filter Card with Collapse Dropdown --}}
            <div class="row">
                <div class="col-lg-12" style="margin-top: -20px">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Filter Expenses</h5>
                            <div>
                                <a href="{{ route('form.expense.list') }}" class="btn btn-sm btn-outline-secondary mr-2">
                                    <i class="fas fa-times mr-1"></i> Clear Filters
                                </a>
                                <button class="btn btn-sm btn-light" type="button" data-toggle="collapse"
                                    data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                    <i class="fas fa-filter mr-1"></i> Show Filters
                                </button>
                            </div>
                        </div>
                        <div class="collapse {{ request()->has('title') || request()->has('expense_type') || request()->has('status') || request()->has('date_from') || request()->has('date_to') ? 'show' : '' }}"
                            id="filterCollapse">
                            <div class="card-body">
                                <form id="expenseFilterForm" action="{{ route('form.expense.list') }}" method="GET">
                                    <div class="row formtype">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="title">Expense Title</label>
                                                <input type="text" name="title" id="title"
                                                    class="form-control" value="{{ request('title') }}"
                                                    placeholder="Search by title" oninput="submitFilterForm()">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="expense_type">Expense Type</label>
                                                <select name="expense_type" id="expense_type" class="form-control"
                                                    onchange="submitFilterForm()">
                                                    <option value="">All Types</option>
                                                    @foreach ($expenseTypes as $expenseType)
                                                        <option value="{{ $expenseType->id }}"
                                                            {{ request('expense_type') == $expenseType->id ? 'selected' : '' }}>
                                                            {{ $expenseType->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select name="status" id="status" class="form-control"
                                                    onchange="submitFilterForm()">
                                                    <option value="">All Status</option>
                                                    <option value="pending"
                                                        {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                                    </option>
                                                    <option value="approved"
                                                        {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                                    </option>
                                                    <option value="rejected"
                                                        {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="date_from">Date From</label>
                                                <input type="date" name="date_from" id="date_from"
                                                    class="form-control" value="{{ request('date_from') }}"
                                                    onchange="submitFilterForm()">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="date_to">Date To</label>
                                                <input type="date" name="date_to" id="date_to"
                                                    class="form-control" value="{{ request('date_to') }}"
                                                    onchange="submitFilterForm()">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Expenses Table --}}
            <div class="row mt-4">
                <div class="col-sm-12" style="margin-top: -40px">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Expense Type</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Receipt</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($expenses as $expense)
                                            <tr>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="#">{{ $expense->title }}</a>
                                                    </h2>
                                                    @if($expense->description)
                                                        <small class="text-muted">{{ Str::limit($expense->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $expense->expenseType->name }}</td>
                                                <td>${{ number_format($expense->amount, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                                                <td>
                                                    <div class="actions">
                                                        @if ($expense->status == 'pending')
                                                            <a href="#"
                                                                class="btn btn-sm bg-warning-light mr-2">Pending</a>
                                                        @elseif ($expense->status == 'approved')
                                                            <a href="#"
                                                                class="btn btn-sm bg-success-light mr-2">Approved</a>
                                                        @elseif ($expense->status == 'rejected')
                                                            <a href="#"
                                                                class="btn btn-sm bg-danger-light mr-2">Rejected</a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($expense->receipt)
                                                        <a href="{{ asset('assets/upload/' . $expense->receipt) }}" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fas fa-file"></i> View
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No receipt</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right shadow-sm py-2"
                                                            style="min-width: 150px; font-size: 15px;">
                                                            <a href="{{ route('form.expense.edit', $expense->id) }}"
                                                                class="dropdown-item px-4 py-2 d-flex align-items-center">
                                                                <i class="fas fa-pencil-alt mr-2"></i> Edit
                                                            </a>
                                                            <form id="delete-form-{{ $expense->id }}"
                                                                action="{{ route('form.expense.delete', $expense->id) }}"
                                                                method="POST" style="display:none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item px-4 py-2 d-flex align-items-center"
                                                                onclick="confirmDelete({{ $expense->id }})">
                                                                <i class="fas fa-trash-alt mr-2"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No expenses found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $expenses->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function confirmDelete(expenseId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + expenseId).submit();
            }
        })
    }

    let timeout = null;

    function submitFilterForm() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            document.getElementById('expenseFilterForm').submit();
        }, 500);
    }
</script>