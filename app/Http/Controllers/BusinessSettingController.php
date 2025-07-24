<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class BusinessSettingController extends Controller
{
    /**
     * Display business settings
     */
    public function index()
    {
        $settings = BusinessSetting::getSettings();
        return view('form.business_settings.index', compact('settings'));
    }
    /**
     * Show the form for editing business settings
     */
    public function edit()
    {
        $settings = BusinessSetting::getSettings();
        return view('form.business_settings.edit', compact('settings'));
    }
    /**
     * Update business settings
     */
    public function update(Request $request)
    {
        $request->validate(BusinessSetting::validationRules());
        try {
            $settings = BusinessSetting::getSettings();
            $data = $request->except(['_token', '_method', 'logo', 'favicon', 'banner_image']);
            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $logoName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
                $logoFile->move(public_path('assets/upload'), $logoName);
                // Delete old logo if exists
                if ($settings->logo && File::exists(public_path('assets/upload/' . $settings->logo))) {
                    File::delete(public_path('assets/upload/' . $settings->logo));
                }
                $data['logo'] = $logoName;
            }
            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                $faviconFile = $request->file('favicon');
                $faviconName = 'favicon_' . time() . '.' . $faviconFile->getClientOriginalExtension();
                $faviconFile->move(public_path('assets/upload'), $faviconName);
                // Delete old favicon if exists
                if ($settings->favicon && File::exists(public_path('assets/upload/' . $settings->favicon))) {
                    File::delete(public_path('assets/upload/' . $settings->favicon));
                }
                $data['favicon'] = $faviconName;
            }
            // Handle banner image upload
            if ($request->hasFile('banner_image')) {
                $bannerFile = $request->file('banner_image');
                $bannerName = 'banner_' . time() . '.' . $bannerFile->getClientOriginalExtension();
                $bannerFile->move(public_path('assets/upload'), $bannerName);
                // Delete old banner if exists
                if ($settings->banner_image && File::exists(public_path('assets/upload/' . $settings->banner_image))) {
                    File::delete(public_path('assets/upload/' . $settings->banner_image));
                }
                $data['banner_image'] = $bannerName;
            }
            BusinessSetting::updateSettings($data);
            Toastr::success('Business settings updated successfully', 'Success');
            return redirect()->route('business-settings.index');
        } catch (\Exception $e) {
            Toastr::error('Failed to update settings: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }
    /**
     * Show general settings tab
     */
    public function general()
    {
        $settings = BusinessSetting::getSettings();
        return view('form.business_settings.general', compact('settings'));
    }
    /**
     * Show contact settings tab
     */
    public function contact()
    {
        $settings = BusinessSetting::getSettings();
        return view('form.business_settings.contact', compact('settings'));
    }
    /**
     * Show branding settings tab
     */
    public function branding()
    {
        $settings = BusinessSetting::getSettings();
        return view('form.business_settings.branding', compact('settings'));
    }
    /**
     * Update specific section
     */
    public function updateSection(Request $request, $section)
    {
        $validSections = ['general', 'contact', 'branding'];
        if (!in_array($section, $validSections)) {
            Toastr::error('Invalid section', 'Error');
            return redirect()->back();
        }
        // Get validation rules based on section
        $rules = $this->getSectionValidationRules($section);
        $request->validate($rules);
        try {
            $data = $request->except(['_token', '_method']);
            // Handle file uploads for branding section
            if ($section === 'branding') {
                $data = $this->handleFileUploads($request, $data);
            }
            BusinessSetting::updateSettings($data);
            Toastr::success(ucfirst($section) . ' settings updated successfully', 'Success');
            return redirect()->route('business-settings.' . $section);
        } catch (\Exception $e) {
            Toastr::error('Failed to update settings: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }
    /**
     * Get validation rules for specific section
     */
    private function getSectionValidationRules($section)
    {
        $allRules = BusinessSetting::validationRules();
        $sectionFields = [
            'general' => ['hotel_name', 'slogan', 'tagline', 'description', 'star_rating'],
            'contact' => ['address', 'city', 'state', 'postal_code', 'country', 'phone', 'phone_secondary', 'email', 'email_support', 'website', 'facebook_url', 'instagram_url', 'linkedin_url'],
            'branding' => ['logo', 'favicon', 'banner_image']
        ];
        return array_intersect_key($allRules, array_flip($sectionFields[$section]));
    }
    /**
     * Handle file uploads
     */
    private function handleFileUploads(Request $request, array $data)
    {
        $settings = BusinessSetting::getSettings();
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(public_path('assets/upload'), $logoName);
            // Delete old logo if exists
            if ($settings->logo && File::exists(public_path('assets/upload/' . $settings->logo))) {
                File::delete(public_path('assets/upload/' . $settings->logo));
            }
            $data['logo'] = $logoName;
        }
        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $faviconFile->getClientOriginalExtension();
            $faviconFile->move(public_path('assets/upload'), $faviconName);
            // Delete old favicon if exists
            if ($settings->favicon && File::exists(public_path('assets/upload/' . $settings->favicon))) {
                File::delete(public_path('assets/upload/' . $settings->favicon));
            }
            $data['favicon'] = $faviconName;
        }
        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $bannerFile = $request->file('banner_image');
            $bannerName = 'banner_' . time() . '.' . $bannerFile->getClientOriginalExtension();
            $bannerFile->move(public_path('assets/upload'), $bannerName);
            // Delete old banner if exists
            if ($settings->banner_image && File::exists(public_path('assets/upload/' . $settings->banner_image))) {
                File::delete(public_path('assets/upload/' . $settings->banner_image));
            }
            $data['banner_image'] = $bannerName;
        }
        return $data;
    }
}
