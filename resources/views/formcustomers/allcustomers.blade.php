@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">Customers</h4> <a
                                href="{{ route('form/addcustomer/page') }}" class="btn btn-primary float-right veiwbutton">Add
                                Customers</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>National ID</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Date of Birth</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Address</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allCustomers as $customers)
                                            <tr>
                                                <td hidden class="id">{{ $customers->id }}</td>
                                                <td hidden class="fileupload">{{ $customers->fileupload }}</td>

                                                <td hidden class="bkg_customer_id">{{ $customers->bkg_customer_id }}</td>
                                                <td hidden class="fileupload">{{ $customers->fileupload }}</td>
                                                <td>{{ $customers->bkg_customer_id }}</td>
                                                <td>{{ $customers->national_id }}</td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="profile.html" class="avatar avatar-sm mr-2">
                                                            <img class="avatar-img rounded-circle"
                                                                src="{{ URL::to('/assets/upload/' . $customers->fileupload) }}"
                                                                alt="{{ $customers->fileupload }}">
                                                        </a>
                                                        <a
                                                            href="profile.html">{{ $customers->name }}<span>{{ $customers->bkg_customer_id }}</span></a>
                                                    </h2>
                                                </td>
                                                <td>{{ $customers->gender }}</td>
                                                <td>{{ $customers->dob }}</td>
                                                <td><a href="#" class="__cf_email__"
                                                        data-cfemail="2652494b4b5f44435448474a66435e474b564a430845494b">{{ $customers->email }}</a>
                                                </td>
                                                <td>{{ $customers->ph_number }}</td>
                                                <td>{{ $customers->address }}</td>
                                                <td>{{ $customers->country }}</td>
                                                <td>
                                                    <div class="actions">
                                                        @if ($customers->status)
                                                            <a href="#"
                                                                class="btn btn-sm bg-success-light mr-2">Active</a>
                                                        @else
                                                            <a href="#"
                                                                class="btn btn-sm bg-danger-light mr-2">Inactive</a>
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
                                                            <a class="dropdown-item"
                                                                href="{{ url('form/customer/edit/' . $customers->bkg_customer_id) }}">
                                                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                            </a>
                                                            <a class="dropdown-item customerDelete" href="#"
                                                                data-toggle="modal" data-target="#delete_asset">
                                                                <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="delete_asset" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <form action="{{ route('form/customer/delete') }}" method="POST">
                            @csrf
                            <img src="{{ URL::to('assets/img/sent.png') }}" alt="" width="50" height="46">
                            <h3 class="delete_class">Are you sure want to delete this Asset?</h3>
                            <div class="m-t-20">
                                <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                                <input class="form-control" type="hidden" id="e_id" name="id" value="{{ $customers->bkg_customer_id }}">
                                <input class="form-control" type="hidden" id="e_fileupload" name="fileupload"
                                    value="">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @push('js')
            <script>
                // Handle Delete Modal
                $(document).on('click', '.customerDelete', function() {
                    var _this = $(this).closest('tr');
                    // Set bkg_customer_id instead of id
                    $('#e_id').val(_this.find('.bkg_customer_id').text().trim());
                    $('#e_fileupload').val(_this.find('.fileupload').text().trim());
                });
            </script>
        @endpush
    @endsection
