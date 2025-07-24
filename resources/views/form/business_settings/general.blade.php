@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mt-5">General Settings</h4>
                        <a href="{{ route('business-settings.index') }}" class="btn btn-secondary float-right">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Settings
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('business-settings.update-section', 'general') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hotel_name">Hotel Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('hotel_name') is-invalid @enderror" 
                                                   id="hotel_name" name="hotel_name" value="{{ old('hotel_name', $settings->hotel_name) }}" required>
                                            @error('hotel_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slogan">Slogan</label>
                                            <input type="text" class="form-control @error('slogan') is-invalid @enderror" 
                                                   id="slogan" name="slogan" value="{{ old('slogan', $settings->slogan) }}">
                                            @error('slogan')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tagline">Tagline</label>
                                            <input type="text" class="form-control @error('tagline') is-invalid @enderror" 
                                                   id="tagline" name="tagline" value="{{ old('tagline', $settings->tagline) }}">
                                            @error('tagline')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="star_rating">Star Rating</label>
                                            <select class="form-control @error('star_rating') is-invalid @enderror" id="star_rating" name="star_rating">
                                                <option value="">Select Rating</option>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}" {{ old('star_rating', $settings->star_rating) == $i ? 'selected' : '' }}>
                                                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('star_rating')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="4">{{ old('description', $settings->description) }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>Update General Settings
                                    </button>
                                    <a href="{{ route('business-settings.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection