<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Clean Energy Is Now',
                'subtitle' => 'Buy at Avora Energy at the Best Price, support & Genuine warranty',
                'description' => 'Panels, batteries, inverters, water heaters and pumping systems from Tier-1 brands. Genuine warranty honoured in Kenya.',
                'image' => 'img/seed/gree-bg-panels.jpg',
                'button_text' => 'Shop Solar Panels',
                'button_url' => '/category/solar-panels',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Solar Flash Sales',
                'subtitle' => 'Blazing deals gone before the sun sets',
                'description' => 'Limited stock at unbeatable prices on selected panels, inverters and batteries.',
                'image' => 'img/seed/flash-sales.jpg',
                'button_text' => 'View Flash Sales',
                'button_url' => '/shop?sale=1',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Black Friday Deals',
                'subtitle' => 'Harvest massive savings this Black Friday',
                'description' => 'Panels, batteries & inverters included. Limited time offers countrywide.',
                'image' => 'img/seed/black-friday.jpg',
                'button_text' => 'Shop Deals',
                'button_url' => '/shop',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate(['title' => $slide['title']], $slide);
        }
    }
}