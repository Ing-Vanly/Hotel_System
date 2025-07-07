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

            {{-- Search Form --}}
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('form.employee.list') }}" method="GET">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="employee_name">Employee Name</label>
                                    <input type="text" name="employee_name" id="employee_name" class="form-control"
                                        value="{{ request('employee_name') }}" placeholder="First or Last Name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" name="role" id="position" class="form-control"
                                        value="{{ request('role') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-success">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Employee Table --}}
            <div class="row mt-4">
                <div class="col-sm-12">
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
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="{{ route('form.employee.edit', $employee->id) }}"
                                                                class="dropdown-item">
                                                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                            </a>
                                                            <form
                                                                action="{{ route('form.employee.delete', $employee->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure want to delete this employee?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                                </button>
                                                            </form>
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
