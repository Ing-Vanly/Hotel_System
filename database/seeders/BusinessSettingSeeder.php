<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessSetting;

class BusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default business settings if none exist
        if (!BusinessSetting::exists()) {
            BusinessSetting::create([
                'hotel_name' => 'HoteLy',
                'slogan' => 'Your Comfort, Our Priority',
                'tagline' => 'Experience Luxury Like Never Before',
                'description' => 'Welcome to Grand Hotel & Resort, where exceptional service meets unparalleled comfort. Our world-class facilities and dedicated staff ensure your stay is memorable and relaxing.',

                // Rating
                'star_rating' => 5,

                // Contact Information
                'address' => 'SiemReap. Cambodia',
                'city' => 'SiemReap',
                'state' => 'Phnom Penh',
                'postal_code' => '+855',
                'country' => 'Cambodia',
                'phone' => '(+855) 96-777-6599',
                'phone_secondary' => '(+855) 96-777-6599',
                'email' => 'ingvanly168@gmail.com',
                'email_support' => 'support@hotely.com',
                'website' => 'https://www.hotely.com',

                // Social Media
                'facebook_url' => 'https://www.facebook.com/share/16wBYEAfcB/?mibextid=wwXIfr',
                'instagram_url' => 'https://www.instagram.com/oungvanly135?igsh=cjVoZ3k1cHV3MzM%3D&utm_source=qr',
                'linkedin_url' => 'https://www.linkedin.com/in/ing-vanly-8802392b8?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app',
            ]);
        }
    }
}
