<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['name' => 'Jinko Solar', 'sort_order' => 1],
            ['name' => 'JA Solar', 'sort_order' => 2],
            ['name' => 'Trina Solar', 'sort_order' => 3],
            ['name' => 'Longi Solar', 'sort_order' => 4],
            ['name' => 'Growatt', 'sort_order' => 5],
            ['name' => 'Deye', 'sort_order' => 6],
            ['name' => 'Victron Energy', 'sort_order' => 7],
            ['name' => 'Huawei Solar', 'sort_order' => 8],
            ['name' => 'Sungrow', 'sort_order' => 9],
            ['name' => 'BYD', 'sort_order' => 10],
            ['name' => 'Felicity Solar', 'sort_order' => 11],
            ['name' => 'TBB Power', 'sort_order' => 12],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(
                ['name' => $partner['name']],
                ['sort_order' => $partner['sort_order'], 'is_active' => true, 'logo' => null]
            );
        }
    }
}
