<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_name',
        'slogan',
        'tagline',
        'description',
        'logo',
        'favicon',
        'banner_image',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'phone_secondary',
        'email',
        'email_support',
        'website',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'registration_number',
        'star_rating',
    ];

    protected $casts = [
        'star_rating' => 'integer',
    ];

    /**
     * Get the singleton instance of business settings
     */
    public static function getSettings()
    {
        return static::first() ?? static::create(static::getDefaultSettings());
    }

    /**
     * Update business settings (singleton pattern)
     */
    public static function updateSettings(array $data)
    {
        $settings = static::first();

        if ($settings) {
            $settings->update($data);
        } else {
            $settings = static::create(array_merge(static::getDefaultSettings(), $data));
        }

        return $settings;
    }

    /**
     * Get default settings for initial setup
     */
    public static function getDefaultSettings()
    {
        return [
            'hotel_name' => 'Hotel Management System',
            'slogan' => 'Your Comfort, Our Priority',
            'address' => '123 Hotel Street',
            'phone' => '+1234567890',
            'email' => 'info@hotel.com',
            'star_rating' => 3,
        ];
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get logo URL with fallback
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo && file_exists(public_path('assets/upload/' . $this->logo))) {
            return asset('assets/upload/' . $this->logo);
        }

        return asset('assets/images/default-logo.png');
    }

    /**
     * Get favicon URL with fallback
     */
    public function getFaviconUrlAttribute()
    {
        if ($this->favicon && file_exists(public_path('assets/upload/' . $this->favicon))) {
            return asset('assets/upload/' . $this->favicon);
        }

        return asset('favicon.ico');
    }

    /**
     * Get banner image URL with fallback
     */
    public function getBannerImageUrlAttribute()
    {
        if ($this->banner_image && file_exists(public_path('assets/upload/' . $this->banner_image))) {
            return asset('assets/upload/' . $this->banner_image);
        }

        return asset('assets/images/default-banner.jpg');
    }

    /**
     * Validation rules for business settings
     */
    public static function validationRules()
    {
        return [
            'hotel_name' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'email_support' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'star_rating' => 'nullable|integer|min:1|max:5',
        ];
    }
}
