<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'general' => [
                'site_name' => 'Avora Energy Solutions',
                'site_tagline' => 'Clean, reliable solar energy for homes and businesses.',
                'logo' => 'img/logo-wide.png',
            ],
            'contact' => [
                'phone_1' => '+254758433424',
                'phone_2' => '',
                'email' => 'faithdoyin3@gmail.com',
                'address_1' => '',
                'address_2' => '',
                'whatsapp_number' => '+254758433424',
            ],
            'social' => [
                'facebook' => '',
                'instagram' => '',
                'youtube' => '',
                'linkedin' => '',
                'tiktok' => '',
            ],
            'seo' => [
                'meta_title' => 'Solar Products in Kenya | Panels, Batteries, Inverters - Avora Energy Solutions',
                'meta_description' => 'Avora Energy Solutions - Solar Panels, Water Heaters, Pumps, Inverters, Batteries, Lights & More. Pricing, nationwide delivery and expert support.',
            ],
            'misc' => [
                'footer_copyright' => 'Avora Energy Solutions. All Rights Reserved.',
                'delivery_note' => 'Nationwide delivery across Kenya.',
                'map_embed' => '',
            ],
        ];

        foreach ($settings as $group => $items) {
            foreach ($items as $key => $value) {
                Setting::set($key, $value, $group);
            }
        }
    }
}