@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mt-5">Edit Business Settings</h4>
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
                            <!-- Navigation Tabs -->
                            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded" id="settingsTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general"
                                        role="tab">
                                        <i class="fas fa-info-circle mr-2"></i>General
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab">
                                        <i class="fas fa-address-book mr-2"></i>Contact
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="branding-tab" data-toggle="tab" href="#branding" role="tab">
                                        <i class="fas fa-palette mr-2"></i>Branding
                                    </a>
                                </li>
                            </ul>

                            <form action="{{ route('business-settings.update') }}" method="POST"
                                enctype="multipart/form-data" id="businessSettingsForm">
                                @csrf
                                @method('PUT')

                                <div class="tab-content" id="settingsTabsContent">
                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="hotel_name">Hotel Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('hotel_name') is-invalid @enderror"
                                                        id="hotel_name" name="hotel_name"
                                                        value="{{ old('hotel_name', $settings->hotel_name) }}" required>
                                                    @error('hotel_name')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="slogan">Slogan</label>
                                                    <input type="text"
                                                        class="form-control @error('slogan') is-invalid @enderror"
                                                        id="slogan" name="slogan"
                                                        value="{{ old('slogan', $settings->slogan) }}">
                                                    @error('slogan')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tagline">Tagline</label>
                                                    <input type="text"
                                                        class="form-control @error('tagline') is-invalid @enderror"
                                                        id="tagline" name="tagline"
                                                        value="{{ old('tagline', $settings->tagline) }}">
                                                    @error('tagline')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="star_rating">Star Rating</label>
                                                    <select class="form-control @error('star_rating') is-invalid @enderror"
                                                        id="star_rating" name="star_rating">
                                                        <option value="">Select Rating</option>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('star_rating', $settings->star_rating) == $i ? 'selected' : '' }}>
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
                                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                                        rows="4">{{ old('description', $settings->description) }}</textarea>
                                                    @error('description')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Tab -->
                                    <div class="tab-pane fade" id="contact" role="tabpanel">
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Address <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('address') is-invalid @enderror"
                                                        id="address" name="address"
                                                        value="{{ old('address', $settings->address) }}" required>
                                                    @error('address')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input type="text"
                                                        class="form-control @error('city') is-invalid @enderror"
                                                        id="city" name="city"
                                                        value="{{ old('city', $settings->city) }}">
                                                    @error('city')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="state">State/Province</label>
                                                    <input type="text"
                                                        class="form-control @error('state') is-invalid @enderror"
                                                        id="state" name="state"
                                                        value="{{ old('state', $settings->state) }}">
                                                    @error('state')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="postal_code">Postal Code</label>
                                                    <input type="text"
                                                        class="form-control @error('postal_code') is-invalid @enderror"
                                                        id="postal_code" name="postal_code"
                                                        value="{{ old('postal_code', $settings->postal_code) }}">
                                                    @error('postal_code')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="country">Country</label>
                                                    <input type="text"
                                                        class="form-control @error('country') is-invalid @enderror"
                                                        id="country" name="country"
                                                        value="{{ old('country', $settings->country) }}">
                                                    @error('country')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Phone <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone" name="phone"
                                                        value="{{ old('phone', $settings->phone) }}" required>
                                                    @error('phone')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone_secondary">Secondary Phone</label>
                                                    <input type="text"
                                                        class="form-control @error('phone_secondary') is-invalid @enderror"
                                                        id="phone_secondary" name="phone_secondary"
                                                        value="{{ old('phone_secondary', $settings->phone_secondary) }}">
                                                    @error('phone_secondary')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email"
                                                        value="{{ old('email', $settings->email) }}" required>
                                                    @error('email')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email_support">Support Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email_support') is-invalid @enderror"
                                                        id="email_support" name="email_support"
                                                        value="{{ old('email_support', $settings->email_support) }}">
                                                    @error('email_support')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="website">Website</label>
                                                    <input type="url"
                                                        class="form-control @error('website') is-invalid @enderror"
                                                        id="website" name="website"
                                                        value="{{ old('website', $settings->website) }}">
                                                    @error('website')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="facebook_url">Facebook URL</label>
                                                    <input type="url"
                                                        class="form-control @error('facebook_url') is-invalid @enderror"
                                                        id="facebook_url" name="facebook_url"
                                                        value="{{ old('facebook_url', $settings->facebook_url) }}">
                                                    @error('facebook_url')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="instagram_url">Instagram URL</label>
                                                    <input type="url"
                                                        class="form-control @error('instagram_url') is-invalid @enderror"
                                                        id="instagram_url" name="instagram_url"
                                                        value="{{ old('instagram_url', $settings->instagram_url) }}">
                                                    @error('instagram_url')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="linkedin_url">LinkedIn URL</label>
                                                    <input type="url"
                                                        class="form-control @error('linkedin_url') is-invalid @enderror"
                                                        id="linkedin_url" name="linkedin_url"
                                                        value="{{ old('linkedin_url', $settings->linkedin_url) }}">
                                                    @error('linkedin_url')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Branding Tab -->
                                    <div class="tab-pane fade" id="branding" role="tabpanel">
                                        <div class="row mt-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="logo">Logo</label>
                                                    <input type="file"
                                                        class="form-control-file @error('logo') is-invalid @enderror"
                                                        id="logo" name="logo" accept="image/*">
                                                    @if ($settings->logo)
                                                        <div class="mt-2">
                                                            <img src="{{ $settings->logo_url }}" alt="Current Logo"
                                                                class="img-thumbnail" style="max-height: 100px;">
                                                        </div>
                                                    @endif
                                                    @error('logo')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="favicon">Favicon</label>
                                                    <input type="file"
                                                        class="form-control-file @error('favicon') is-invalid @enderror"
                                                        id="favicon" name="favicon" accept="image/*">
                                                    @if ($settings->favicon)
                                                        <div class="mt-2">
                                                            <img src="{{ $settings->favicon_url }}" alt="Current Favicon"
                                                                class="img-thumbnail" style="max-height: 50px;">
                                                        </div>
                                                    @endif
                                                    @error('favicon')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="banner_image">Banner Image</label>
                                                    <input type="file"
                                                        class="form-control-file @error('banner_image') is-invalid @enderror"
                                                        id="banner_image" name="banner_image" accept="image/*">
                                                    @if ($settings->banner_image)
                                                        <div class="mt-2">
                                                            <img src="{{ $settings->banner_image_url }}"
                                                                alt="Current Banner" class="img-thumbnail"
                                                                style="max-height: 100px;">
                                                        </div>
                                                    @endif
                                                    @error('banner_image')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="text-right mt-4">
                                    <a href="{{ route('business-settings.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>Update Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
