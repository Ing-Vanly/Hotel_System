@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mt-5">Branding Settings</h4>
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
                            <form action="{{ route('business-settings.update-section', 'branding') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="logo">Logo</label>
                                            <input type="file" class="form-control-file @error('logo') is-invalid @enderror" 
                                                   id="logo" name="logo" accept="image/*">
                                            @if($settings->logo)
                                                <div class="mt-2">
                                                    <img src="{{ $settings->logo_url }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                                    <p class="text-muted small mt-1">Current Logo</p>
                                                </div>
                                            @endif
                                            <small class="text-muted">Recommended: PNG, JPG, GIF (Max: 2MB)</small>
                                            @error('logo')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="favicon">Favicon</label>
                                            <input type="file" class="form-control-file @error('favicon') is-invalid @enderror" 
                                                   id="favicon" name="favicon" accept="image/*">
                                            @if($settings->favicon)
                                                <div class="mt-2">
                                                    <img src="{{ $settings->favicon_url }}" alt="Current Favicon" class="img-thumbnail" style="max-height: 50px;">
                                                    <p class="text-muted small mt-1">Current Favicon</p>
                                                </div>
                                            @endif
                                            <small class="text-muted">Recommended: ICO, PNG (Max: 1MB)</small>
                                            @error('favicon')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="banner_image">Banner Image</label>
                                            <input type="file" class="form-control-file @error('banner_image') is-invalid @enderror" 
                                                   id="banner_image" name="banner_image" accept="image/*">
                                            @if($settings->banner_image)
                                                <div class="mt-2">
                                                    <img src="{{ $settings->banner_image_url }}" alt="Current Banner" class="img-thumbnail" style="max-height: 100px;">
                                                    <p class="text-muted small mt-1">Current Banner</p>
                                                </div>
                                            @endif
                                            <small class="text-muted">Recommended: PNG, JPG (Max: 5MB)</small>
                                            @error('banner_image')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>Update Branding Settings
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