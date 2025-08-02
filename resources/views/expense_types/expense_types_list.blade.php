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
                            <h4 class="card-title">Expense Types</h4>
                            <a href="{{ route('form.expensetype.add') }}" class="btn btn-primary">Add Expense Type</a>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Expense Types Table --}}
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($expenseTypes as $expenseType)
                                            <tr>
                                                <td>{{ $expenseType->name }}</td>
                                                <td>{{ $expenseType->description ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="actions">
                                                        @if ($expenseType->status == 'active')
                                                            <a href="#"
                                                                class="btn btn-sm bg-success-light mr-2">Active</a>
                                                        @else
                                                            <a href="#"
                                                                class="btn btn-sm bg-danger-light mr-2">Inactive</a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($expenseType->created_at)->format('d M Y') }}
                                                </td>
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right shadow-sm py-2"
                                                            style="min-width: 150px; font-size: 15px;">
                                                            <a href="{{ route('form.expensetype.edit', $expenseType->id) }}"
                                                                class="dropdown-item px-4 py-2 d-flex align-items-center">
                                                                <i class="fas fa-pencil-alt mr-2"></i> Edit
                                                            </a>
                                                            <form id="delete-form-{{ $expenseType->id }}"
                                                                action="{{ route('form.expensetype.delete', $expenseType->id) }}"
                                                                method="POST" style="display:none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item px-4 py-2 d-flex align-items-center"
                                                                onclick="confirmDelete({{ $expenseType->id }})">
                                                                <i class="fas fa-trash-alt mr-2"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No expense types found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $expenseTypes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function confirmDelete(expenseTypeId) {
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
                document.getElementById('delete-form-' + expenseTypeId).submit();
            }
        })
    }
</script>