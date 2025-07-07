@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <h4 class="mt-4">Add New Role</h4>
        <form action="{{ route('role.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Role Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter role name" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('role.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
