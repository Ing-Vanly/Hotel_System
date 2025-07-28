    @extends('layouts.master')
    @section('content')
        {{-- message --}}
        {!! Toastr::message() !!}
        <div class="page-wrapper">
            <div class="content container-fluid">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mt-5">
                                <h4 class="card-title float-left mt-2">
                                    <i class="fas fa-users mr-2"></i>User Management
                                </h4>
                                <a href="{{ route('users/add/new') }}" class="btn btn-primary float-right">
                                    <i class="fas fa-plus mr-2"></i>Add New User
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('users/list/page') }}" id="filterForm">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="role">Filter by Role:</label>
                                                <select name="role" id="role" class="form-control">
                                                    <option value="">All Roles</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}"
                                                            {{ request('role') == $role->name ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="status">Filter by Status:</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="">All Status</option>
                                                    <option value="active"
                                                        {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="inactive"
                                                        {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="gender">Filter by Gender:</label>
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="">All Genders</option>
                                                    <option value="male"
                                                        {{ request('gender') == 'male' ? 'selected' : '' }}>
                                                        Male</option>
                                                    <option value="female"
                                                        {{ request('gender') == 'female' ? 'selected' : '' }}>Female
                                                    </option>
                                                    <option value="other"
                                                        {{ request('gender') == 'other' ? 'selected' : '' }}>
                                                        Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="per_page">Show Per Page:</label>
                                                <select name="per_page" id="per_page" class="form-control">
                                                    @foreach ($validPerPageOptions as $option)
                                                        <option value="{{ $option }}"
                                                            {{ $perPage == $option ? 'selected' : '' }}>
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search">Search:</label>
                                                <div class="input-group">
                                                    <input type="text" name="search" id="search" class="form-control"
                                                        placeholder="Search users..." value="{{ request('search') }}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" type="submit">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-secondary" id="clear-filters">
                                                <i class="fas fa-times mr-2"></i>Clear Filters
                                            </button>
                                            <button type="submit" class="btn btn-primary ml-2">
                                                <i class="fas fa-filter mr-2"></i>Apply Filters
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-list mr-2"></i>Users List
                                    <small class="text-muted">
                                        ({{ $users->total() }} total users, showing {{ $users->firstItem() ?? 0 }} to
                                        {{ $users->lastItem() ?? 0 }})
                                    </small>
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($users->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center mb-0" id="UsersList">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th><i class="fas fa-id-card mr-1"></i>ID</th>
                                                    <th><i class="fas fa-user mr-1"></i>Name</th>
                                                    <th><i class="fas fa-venus-mars mr-1"></i>Gender</th>
                                                    <th><i class="fas fa-birthday-cake mr-1"></i>Age</th>
                                                    <th><i class="fas fa-user-tag mr-1"></i>Role</th>
                                                    <th><i class="fas fa-envelope mr-1"></i>Email</th>
                                                    <th><i class="fas fa-phone mr-1"></i>Phone</th>
                                                    <th><i class="fas fa-toggle-on mr-1"></i>Status</th>
                                                    <th><i class="fas fa-cogs mr-1"></i>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                    @php
                                                        // Handle status - default to 'active' if null or column doesn't exist
$status = 'active';
if (Schema::hasColumn('users', 'status') && $user->status) {
    $status = $user->status;
}
$badgeClass =
    $status === 'active' ? 'badge-success' : 'badge-danger';

// Handle gender display
$genderDisplay = '<span class="text-muted">-</span>';
if ($user->gender) {
    if ($user->gender === 'male') {
        $genderDisplay =
            '<i class="fas fa-mars text-primary mr-1"></i>Male';
    } elseif ($user->gender === 'female') {
        $genderDisplay =
            '<i class="fas fa-venus text-danger mr-1"></i>Female';
    } else {
        $genderDisplay =
            '<i class="fas fa-genderless text-info mr-1"></i>Other';
    }
}

$age = $user->age
    ? $user->age . ' years'
    : '<span class="text-muted">-</span>';
$phone =
    $user->phone_number ?: '<span class="text-muted">-</span>';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $user->user_id ?: 'N/A' }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($user->image)
                                                                    <img src="{{ asset('assets/user_image/' . ltrim($user->image, '/')) }}"
                                                                        alt="User Image" class="rounded-circle mr-2"
                                                                        style="width: 40px; height: 40px; object-fit: cover;"
                                                                        onerror="this.onerror=null;this.src='{{ asset('assets/img/placeholder.jpg') }}';">
                                                                @else
                                                                    <img src="{{ asset('assets/img/placeholder.jpg') }}"
                                                                        alt="User Image" class="rounded-circle mr-2"
                                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                                @endif
                                                                <div>
                                                                    <strong>{{ $user->name }}</strong>
                                                                    @if ($user->join_date)
                                                                        <br><small class="text-muted">Joined:
                                                                            {{ \Carbon\Carbon::parse($user->join_date)->format('M d, Y') }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{!! $genderDisplay !!}</td>
                                                        <td>{!! $age !!}</td>
                                                        <td>
                                                            <span
                                                                class="badge badge-info">{{ $user->role ?: 'No Role' }}</span>
                                                        </td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{!! $phone !!}</td>
                                                        <td>
                                                            <div class="actions">
                                                                @if ($status === 'active')
                                                                    <a href="#"
                                                                        class="btn btn-sm bg-success-light mr-2">Active</a>
                                                                @elseif ($status === 'inactive')
                                                                    <a href="#"
                                                                        class="btn btn-sm bg-danger-light mr-2">Inactive</a>
                                                                @elseif ($status === 'terminated')
                                                                    <a href="#"
                                                                        class="btn btn-sm bg-warning-light mr-2">Terminated</a>
                                                                @else
                                                                    <a href="#"
                                                                        class="btn btn-sm bg-secondary-light mr-2">{{ ucfirst($status) }}</a>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                    type="button" data-toggle="dropdown">
                                                                    <i class="fas fa-cog"></i> Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('users/add/edit/' . $user->user_id) }}">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </a>
                                                                    <a class="dropdown-item     delete-user"
                                                                        href="#" data-id="{{ $user->id }}"
                                                                        data-name="{{ $user->name }}">
                                                                        <i class="fas fa-trash"></i> Delete
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                            <!-- Pagination Section -->
                            @if ($users->hasPages())
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="pagination-info">
                                            <span class="text-muted">
                                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                                                {{ $users->total() }} results
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pagination-wrapper float-right">
                                            {{ $users->links() }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No users found</h5>
                                <p class="text-muted">
                                    @if (request()->hasAny(['role', 'status', 'gender', 'search']))
                                        Try adjusting your filters or
                                        <a href="{{ route('users/list/page') }}" class="btn btn-link p-0">clear
                                            all
                                            filters</a>
                                    @else
                                        <a href="{{ route('users/add/new') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-2"></i>Create your first user
                                        </a>
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endsection

    @section('style')
        <style>
            .pagination-wrapper .pagination {
                margin-bottom: 0;
            }

            .pagination-wrapper .page-link {
                border-radius: 4px;
                margin: 0 2px;
                border: 1px solid #dee2e6;
            }

            .pagination-wrapper .page-link:hover {
                background-color: #e9ecef;
                border-color: #dee2e6;
            }

            .pagination-wrapper .page-item.active .page-link {
                background-color: #007bff;
                border-color: #007bff;
            }

            .pagination-info {
                padding: 8px 0;
                margin: 0;
            }

            @media (max-width: 768px) {
                .pagination-wrapper {
                    float: none !important;
                    text-align: center;
                    margin-top: 10px;
                }

                .pagination-info {
                    text-align: center;
                }
            }
        </style>
    @endsection

    @section('script')
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                // Clear filters functionality
                $('#clear-filters').click(function() {
                    $('#filterForm')[0].reset();
                    window.location.href = '{{ route('users/list/page') }}';
                });

                // Auto-submit form when filters change
                $('#role, #status, #gender, #per_page').change(function() {
                    $('#filterForm').submit();
                });

                // Delete user with SweetAlert
                $(document).on('click', '.delete-user', function(e) {
                    e.preventDefault();

                    var userId = $(this).data('id');
                    var userName = $(this).data('name');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You want to delete user "' + userName +
                            '"? This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        background: '#fff',
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Deleting...',
                                text: 'Please wait while we delete the user.',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Perform delete request
                            $.ajax({
                                url: '{{ url('users/delete') }}/' + userId,
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    id: userId
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: response.message,
                                            icon: 'success',
                                            timer: 2000,
                                            showConfirmButton: false
                                        }).then(() => {
                                            location
                                                .reload(); // Reload the page to update the list
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: response.message,
                                            icon: 'error'
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Something went wrong. Please try again.',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endsection
