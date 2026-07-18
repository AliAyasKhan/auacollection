<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'store_name' => 'AUA Collection',
            'store_email' => 'info@auacollection.com',
            'store_phone' => '+92 300 1234567',
            'store_address' => 'M.M. Alam Road, Gulberg III, Lahore, Pakistan',
            'currency' => 'PKR',
            'currency_symbol' => 'Rs.',
            'timezone' => 'Asia/Karachi',
            'facebook_link' => 'https://facebook.com/auacollection',
            'instagram_link' => 'https://instagram.com/auacollection',
            'tiktok_link' => 'https://tiktok.com/@auacollection',
            'whatsapp_number' => '+923001234567',
            'shipping_charges' => '250.00',
            'tax_percentage' => '5.00',
            'seo_meta_title' => 'AUA Collection | Premium Luxury Fashion & Clothing',
            'seo_meta_description' => 'Discover premium luxury clothing for Men, Women, and Kids at AUA Collection. Elevate your fashion with our elegant, Apple-inspired minimal designs.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
