<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = storage_path('app/private/seed/all_products.json');
        $brands = collect();

        if (is_file($jsonPath)) {
            $data = json_decode((string) file_get_contents($jsonPath), true) ?: [];

            foreach ($data as $item) {
                $inferred = $item['brand'] ?? null;
                if (is_string($inferred) && trim($inferred) !== '') {
                    $brands->push(trim($inferred));
                }

                foreach (($item['categories'] ?? []) as $cat) {
                    if (str_starts_with((string) $cat, 'brand\\')) {
                        $brands->push(Str::title(str_replace('-', ' ', Str::after($cat, 'brand\\'))));
                    }
                }
            }

            $brands = $brands->map(fn ($b) => Str::title(str_replace('-', ' ', $b)))->unique();
        }

        $extra = ['Jinko Solar', 'JA Solar', 'Trina Solar', 'Longi Solar', 'Growatt', 'Deye',
            'Victron Energy', 'Huawei Solar', 'Sungrow', 'BYD', 'Felicity Solar', 'TBB Power',
            'Hinen Solar', 'Renergy', 'Vestwoods', 'Chromagen', 'Megasun', 'Eraslan', 'Taico', 'Must'];

        $all = collect($extra)->merge($brands)->unique();

        foreach ($all as $name) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => null,
                    'is_active' => true,
                    'logo' => null,
                ]
            );
        }
    }
}