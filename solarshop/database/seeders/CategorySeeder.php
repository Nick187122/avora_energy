<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Mirrors the product-category taxonomy of the source site (solarshop.co.ke).
     *
     * @var array<string, array{name: string, image: string|null, children: array<string, string>}>
     */
    private array $tree = [
        'solar-panels' => [
            'name' => 'Solar Panels',
            'image' => 'img/seed/panels-bg.jpg',
            'children' => [
                'monocrystalline-solar-panels' => 'Monocrystalline',
                'polycrystalline-solar-panels' => 'Polycrystalline',
                'bifacial-solar-panel' => 'Bifacial',
                'half-cell-topcon-solar-panels' => 'Half-Cell & TOPCon',
                'p-type-solar-panels' => 'P-Type',
                'portable-solar-panel' => 'Portable',
            ],
        ],
        'solar-inverters' => [
            'name' => 'Solar Inverters',
            'image' => 'img/seed/solar-inverters.jpg',
            'children' => [
                'hybrid-offgrid-inverters' => 'Hybrid / Off-grid',
                'hybrid-ongrid-inverters' => 'Hybrid On-grid',
                'grid-tied-commercial-ongrid' => 'Grid-Tied / Commercial',
                'single-phase-inverters' => 'Single Phase Inverters',
                'high-voltage-solar-inverters' => 'High Voltage Inverters',
                'bidirectional-inverter-chargers' => 'Bidirectional Inverter Chargers',
                'quattro-bidirectional-inverters' => 'Quattro Bidirectional Inverters',
                'power-backup-inverter-chargers' => 'Power Backup Inverter Chargers',
                'power-backup-kits' => 'Power Backup Kits',
                'three-phase-commercial-inverters' => 'Three Phase Commercial',
                'water-pumping-inverters' => 'Water Pumping',
                'all-in-one-energy-storage-systems' => 'All-in-One ESS',
                'plug-play-small-inverters' => 'Plug & Play Small',
            ],
        ],
        'solar-batteries' => [
            'name' => 'Solar Batteries',
            'image' => 'img/seed/solar-batteries.jpg',
            'children' => [
                'lithium-batteries-lifepo4' => 'Lithium Batteries (LiFePO4)',
                'high-voltage-lithium-batteries' => 'High Voltage Lithium',
                'low-voltage-battery' => 'Low Voltage Batteries',
                'lithium-ion-bmu' => 'Lithium-ion Battery Management',
                'telecom-batteries' => 'Telecom Batteries',
                'lead-acid-agm-batteries' => 'Lead Acid AGM',
                'lead-acid-gel-batteries' => 'Lead Acid Gel',
                'lead-acid-tubular-batteries' => 'Lead Acid Tubular',
                'carbon-batteries' => 'Carbon Batteries',
                'battery-accessories-bms' => 'Battery Accessories & BMS',
            ],
        ],
        'solar-water-heaters' => [
            'name' => 'Solar Water Heaters',
            'image' => 'img/seed/wh.jpg',
            'children' => [
                'centralized-split-solar-water-heaters' => 'Centralized / Split Systems',
                'flat-plate-direct-systems' => 'Flat Plate Direct',
                'flat-plate-indirect-systems' => 'Flat Plate Indirect',
                'heat-pipe-systems' => 'Heat Pipe Systems',
                'heat-pumps-buy-solar-water-heaters-in-kenya' => 'Heat Pumps',
                'non-pressurized-tube-collectors' => 'Non-Pressurized Tube Collectors',
                'vac-rod-split-pressurized' => 'Vac-Rod Split Pressurized',
            ],
        ],
        'solar-outdoor-lights' => [
            'name' => 'Solar Outdoor Lights',
            'image' => 'img/seed/blue-solar-pumping-inverters.jpg',
            'children' => [
                'solar-flood-lights' => 'Flood Lights',
                'solar-garden-lights' => 'Garden Lights',
                'solar-stand-alone-lights' => 'Stand-Alone Lights',
                'solar-street-lights' => 'Street Lights',
                'solar-strip-led-lights' => 'Strip LED Lights',
                'solar-wall-lights' => 'Wall Lights',
            ],
        ],
        'solar-water-pumps' => [
            'name' => 'Solar Water Pumps',
            'image' => null,
            'children' => [
                'solar-water-pumping-kits' => 'Water Pumping Kits',
            ],
        ],
        'solar-charge-controllers' => [
            'name' => 'Solar Charge Controllers',
            'image' => null,
            'children' => [
                'mppt-solar-charge-controllers' => 'MPPT',
                'pwm-solar-charge-controllers' => 'PWM',
                'smart-solar-charge-controllers' => 'Smart Controllers',
            ],
        ],
        'portable-power-stations' => [
            'name' => 'Portable Power Stations',
            'image' => 'img/seed/growatt.jpg',
            'children' => [
                'portable-power-stations-accessories' => 'Power Station Accessories',
            ],
        ],
        'energy-management-and-monitoring' => [
            'name' => 'Energy Management & Monitoring',
            'image' => null,
            'children' => [],
        ],
        'solar-dc-accessories' => [
            'name' => 'Solar DC Accessories',
            'image' => null,
            'children' => [],
        ],
        'solar-racking-mounting-system' => [
            'name' => 'Solar Racking & Mounting',
            'image' => null,
            'children' => [],
        ],
        'solar-water-heater-accessories' => [
            'name' => 'Solar Water Heater Accessories',
            'image' => null,
            'children' => [],
        ],
        'borehole-solarization-kits' => [
            'name' => 'Borehole Solarization Kits',
            'image' => null,
            'children' => [],
        ],
    ];

    public function run(): void
    {
        $order = 1;

        foreach ($this->tree as $slug => $data) {
            $parent = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => null,
                    'name' => $data['name'],
                    'description' => 'Shop '.$data['name'].' in Kenya.',
                    'image' => $data['image'],
                    'sort_order' => $order++,
                    'is_active' => true,
                ]
            );

            foreach ($data['children'] as $childSlug => $childName) {
                Category::updateOrCreate(
                    ['slug' => $childSlug],
                    [
                        'parent_id' => $parent->id,
                        'name' => $childName,
                        'description' => 'Shop '.$childName.' '.$data['name'].' in Kenya.',
                        'image' => null,
                        'sort_order' => 0,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
