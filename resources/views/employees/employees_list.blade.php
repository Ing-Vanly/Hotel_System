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
                                <h4 class="card-title">Employees</h4>
                                <a href="{{ route('form.employee.add') }}" class="btn btn-primary">Add Employee</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Filter Card with Collapse Dropdown --}}
                <div class="row">
                    <div class="col-lg-12" style="margin-top: -20px">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Filter Employees</h5>
                                <button class="btn btn-sm btn-light" type="button" data-toggle="collapse"
                                    data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                    <i class="fas fa-filter mr-1"></i> Show Filters
                                </button>
                            </div>
                            <div class="collapse {{ request()->has('employee_name') || request()->has('role') || request()->has('status') ? 'show' : '' }}"
                                id="filterCollapse">
                                <div class="card-body">
                                    <form id="employeeFilterForm" action="{{ route('form.employee.list') }}" method="GET">
                                        <div class="row formtype">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="employee_name">Employee Name</label>
                                                    <input type="text" name="employee_name" id="employee_name"
                                                        class="form-control" value="{{ request('employee_name') }}"
                                                        placeholder="First or Last Name" oninput="submitFilterForm()">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="position">Position</label>
                                                    <select name="role" id="position" class="form-control"
                                                        onchange="submitFilterForm()">
                                                        <option value="">All Positions</option>
                                                        @foreach ($positions as $position)
                                                            <option value="{{ $position }}"
                                                                {{ request('role') == $position ? 'selected' : '' }}>
                                                                {{ ucfirst($position) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select name="status" id="status" class="form-control"
                                                        onchange="submitFilterForm()">
                                                        <option value="">Select All</option>
                                                        <option value="active"
                                                            {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                                        </option>
                                                        <option value="inactive"
                                                            {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                                        </option>
                                                        <option value="terminated"
                                                            {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Employee Table --}}
                <div class="row mt-4">
                    <div class="col-sm-12" style="margin-top: -40px">
                        <div class="card card-table">
                            <div class="card-body booking_card">
                                <div class="table-responsive">
                                    <table class="datatable table table-hover table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>National ID</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Position</th>
                                                <th>Joining Date</th>
                                                <th>Status</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($employees as $employee)
                                                <tr>
                                                    <td>
                                                        <h2 class="table-avatar">
                                                            <a href="#" class="avatar avatar-sm mr-2">
                                                                <img class="avatar-img rounded-circle"
                                                                    src="{{ $employee->photo ? asset('assets/upload/' . $employee->photo) : asset('images/default-user.png') }}"
                                                                    alt="Employee Photo">
                                                            </a>
                                                            <a href="#">{{ $employee->first_name }}
                                                                {{ $employee->last_name }}</a>
                                                        </h2>
                                                    </td>
                                                    <td>{{ $employee->national_id }}</td>
                                                    <td>{{ $employee->email }}</td>
                                                    <td>{{ $employee->phone }}</td>
                                                    <td>{{ $employee->position }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="actions">
                                                            @if ($employee->status == 'active')
                                                                <a href="#"
                                                                    class="btn btn-sm bg-success-light mr-2">Active</a>
                                                            @elseif ($employee->status == 'inactive')
                                                                <a href="#"
                                                                    class="btn btn-sm bg-danger-light mr-2">Inactive</a>
                                                            @elseif ($employee->status == 'terminated')
                                                                <a href="#"
                                                                    class="btn btn-sm bg-warning-light mr-2">Terminated</a>
                                                            @else
                                                                <a href="#"
                                                                    class="btn btn-sm bg-secondary-light mr-2">{{ ucfirst($employee->status) }}</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right shadow-sm py-2"
                                                                style="min-width: 150px; font-size: 15px;">
                                                                <a href="{{ route('form.employee.edit', $employee->id) }}"
                                                                    class="dropdown-item px-4 py-2 d-flex align-items-center">
                                                                    <i class="fas fa-pencil-alt mr-2"></i> Edit
                                                                </a>
                                                                <form id="delete-form-{{ $employee->id }}"
                                                                    action="{{ route('form.employee.delete', $employee->id) }}"
                                                                    method="POST" style="display:none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                                <a href="javascript:void(0);"
                                                                    class="dropdown-item px-4 py-2 d-flex align-items-center"
                                                                    onclick="confirmDelete({{ $employee->id }})">
                                                                    <i class="fas fa-trash-alt mr-2"></i> Delete
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No employees found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $employees->appends(request()->input())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    <script>
        // Delete Employee Confirmation
        function confirmDelete(employeeId) {
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
                    // Submit the form with matching id
                    document.getElementById('delete-form-' + employeeId).submit();
                }
            })
        }
        // Filtwe
        let timeout = null;

        function submitFilterForm() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                document.getElementById('employeeFilterForm').submit();
            }, 500);
        }
    </script>
