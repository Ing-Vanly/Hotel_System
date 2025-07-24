@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mt-5">Business Settings</h4>
                        <a href="{{ route('business-settings.edit') }}" class="btn btn-primary float-right">
                            <i class="fas fa-edit mr-2"></i>Edit Settings
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- General Information -->
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">General Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="business-logo mb-3 text-center">
                                @if ($settings->logo)
                                    <img src="{{ $settings->logo_url }}" alt="Hotel Logo" class="img-fluid"
                                        style="max-height: 80px;">
                                @else
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                        <p class="text-muted mb-0">No Logo</p>
                                    </div>
                                @endif
                            </div>
                            <h6 class="text-primary">{{ $settings->hotel_name }}</h6>
                            @if ($settings->slogan || $settings->description)
                                <div class="mb-2" style="margin-bottom: 0.5rem !important;">
                                    @if ($settings->slogan)
                                        <p class="text-muted mb-1" style="margin-bottom: 0.25rem !important;">
                                            <em>"{{ $settings->slogan }}"</em>
                                        </p>
                                    @endif
                                    {{-- @if ($settings->description)
                                        <p class="small text-muted mb-0">{{ Str::limit($settings->description, 100) }}</p>
                                    @endif --}}
                                </div>
                            @endif
                            @if ($settings->star_rating)
                                <div class="mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star {{ $i <= $settings->star_rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ml-2 text-muted">({{ $settings->star_rating }}
                                        Star{{ $settings->star_rating > 1 ? 's' : '' }})</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="contact-info">
                                <div class="mb-3">
                                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                    <strong>Address:</strong><br>
                                    <span class="text-muted">{{ $settings->formatted_address }}</span>
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-phone text-primary mr-2"></i>
                                    <strong>Phone:</strong><br>
                                    <span class="text-muted">{{ $settings->phone }}</span>
                                    @if ($settings->phone_secondary)
                                        <br><span class="text-muted">{{ $settings->phone_secondary }}</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-envelope text-primary mr-2"></i>
                                    <strong>Email:</strong><br>
                                    <span class="text-muted">{{ $settings->email }}</span>
                                    @if ($settings->email_support)
                                        <br><span class="text-muted">{{ $settings->email_support }}</span>
                                    @endif
                                </div>
                                @if ($settings->website)
                                    <div class="mb-3">
                                        <i class="fas fa-globe text-primary mr-2"></i>
                                        <strong>Website:</strong><br>
                                        <a href="{{ $settings->website }}" target="_blank"
                                            class="text-muted">{{ $settings->website }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Social Media -->
                @if ($settings->facebook_url || $settings->twitter_url || $settings->instagram_url || $settings->linkedin_url)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Social Media Links</h5>
                            </div>
                            <div class="card-body">
                                <div class="social-links text-center">
                                    @if ($settings->facebook_url)
                                        <a href="{{ $settings->facebook_url }}" target="_blank"
                                            class="btn btn-primary mr-2">
                                            <i class="fab fa-facebook-f"></i> Facebook
                                        </a>
                                    @endif
                                    @if ($settings->instagram_url)
                                        <a href="{{ $settings->instagram_url }}" target="_blank"
                                            class="btn btn-danger mr-2">
                                            <i class="fab fa-instagram"></i> Instagram
                                        </a>
                                    @endif
                                    @if ($settings->linkedin_url)
                                        <a href="{{ $settings->linkedin_url }}" target="_blank" class="btn btn-primary">
                                            <i class="fab fa-linkedin-in"></i> LinkedIn
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
