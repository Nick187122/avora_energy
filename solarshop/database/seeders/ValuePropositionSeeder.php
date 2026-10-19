<?php

namespace Database\Seeders;

use App\Models\ValueProposition;
use Illuminate\Database\Seeder;

class ValuePropositionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'Online + Physical', 'subtitle' => 'Nairobi showroom. Project teams available across Nairobi Metropolitan.', 'sort_order' => 1],
            ['title' => '10+ Years Experience', 'subtitle' => 'Thousands of successful residential and commercial solar projects delivered across Kenya.', 'sort_order' => 2],
            ['title' => 'Genuine Warranty', 'subtitle' => 'Original products from authorized sources. Manufacturer warranty honoured in Kenya. Local spare parts.', 'sort_order' => 3],
            ['title' => 'Full Project Service', 'subtitle' => 'Design, supply, install, commission, and O&M — from first call to 25-year system life.', 'sort_order' => 4],
        ];

        foreach ($items as $item) {
            ValueProposition::updateOrCreate(
                ['title' => $item['title']],
                array_merge($item, ['icon' => null, 'is_active' => true])
            );
        }
    }
}
