@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">All List Users</h4>
                            <a href="{{ route('users/add/new') }}" class="btn btn-primary float-right veiwbutton" id="userDelete">Add User</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-center mb-0" id="UsersList">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Age</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Status</th>
                                            <th>Modify</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
       $('#UsersList').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            ajax: {
                url: "{{ route('get-users-data') }}",
            },
            columns: [
                { data: 'user_id', name: 'user_id' },
                { data: 'name', name: 'name' },
                { data: 'gender', name: 'gender' },
                { data: 'age', name: 'age' },
                { data: 'role', name: 'role' },
                { data: 'email', name: 'email' },
                { data: 'phone_number', name: 'phone_number' },
                { data: 'status', name: 'status' },
                { data: 'modify', name: 'modify', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection

@endsection
