@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <h4 class="mt-4">Edit Role</h4>
        <form action="{{ route('role.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Role Name</label>
                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="{{ route('role.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
