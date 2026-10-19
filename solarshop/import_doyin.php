<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Str;

echo "=== Importing Doyin Kenya Products ===\n\n";

// 1. Create/find Doyin brand
$doyinBrand = Brand::firstOrCreate(
    ['slug' => 'doyin-kenya'],
    [
        'name' => 'Doyin Kenya',
        'description' => 'Doyin Kenya - Quality water pumps and accessories for solar water pumping systems.',
        'is_active' => true,
        'logo' => null,
    ]
);
echo "Brand: {$doyinBrand->name} (ID: {$doyinBrand->id})\n";

// 2. Create subcategories under Solar Water Pumps
$pumpParent = Category::where('slug', 'solar-water-pumps')->first();
$kitCat = Category::where('slug', 'solar-water-pumping-kits')->first();

$categoryMap = [
    'DC PUMP ordinary impeller' => 'DC Solar Pumps (Ordinary Impeller)',
    'DC PUMP stainless steel impeller' => 'DC Solar Pumps (Stainless Steel)',
    'AC/DC hybrid PUMP' => 'AC/DC Hybrid Solar Pumps',
    'AC PUMP ordinary impeller' => 'AC Solar Pumps',
    'AC booster PUMP' => 'Booster Pumps',
    'AC well PUMP' => 'Deep Well Pumps',
    'Sewage PUMP' => 'Sewage & Drainage Pumps',
    'Accessories' => 'Pump Accessories',
    'AC PUMP Motor' => 'Pump Motors',
    'AC Controller' => 'Pump Controllers',
    'Pump Body - Stainless Steel' => 'Pump Bodies & Parts',
];

$createdCategories = [];
foreach ($categoryMap as $doyinName => $solarshopName) {
    $slug = Str::slug($solarshopName);
    $cat = Category::firstOrCreate(
        ['slug' => $slug],
        [
            'parent_id' => $pumpParent->id,
            'name' => $solarshopName,
            'description' => "Shop {$solarshopName} in Kenya.",
            'image' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]
    );
    $createdCategories[$doyinName] = $cat;
    echo "Category: {$cat->name} (ID: {$cat->id}, Slug: {$cat->slug})\n";
}

// 3. Load Doyin products from their database
// We'll query the Doyin database directly
$doyinDbPath = 'C:/DOYIN KENYA/doyin-backend/database/database.sqlite';
if (! file_exists($doyinDbPath)) {
    exit("Doyin database not found at $doyinDbPath\n");
}

$pdo = new PDO("sqlite:$doyinDbPath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query('
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id
');
$doyinProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\nFound ".count($doyinProducts)." Doyin products to import.\n\n";

$imported = 0;
$skipped = 0;

foreach ($doyinProducts as $dp) {
    $doyinCatName = $dp['category_name'] ?? 'Accessories';
    $solarshopCat = $createdCategories[$doyinCatName] ?? $kitCat;

    // Generate slug from name
    $slug = Str::slug($dp['name']);
    if (Product::where('slug', $slug)->exists()) {
        $slug .= '-'.$dp['id'];
    }

    // Generate SKU
    $sku = strtoupper(Str::slug($dp['name'], '-'));
    if (Product::where('sku', $sku)->exists()) {
        $sku .= '-'.$dp['id'];
    }

    // Estimate price based on pump type and power
    $price = estimatePrice($dp['name'], $dp['category_id']);
    $salePrice = $price * 0.9; // 10% discount

    // Build specifications
    $specs = [];
    if ($dp['max_flow_rate']) {
        $specs['Max Flow Rate'] = $dp['max_flow_rate'];
    }
    if ($dp['max_height']) {
        $specs['Max Head'] = $dp['max_height'];
    }
    if ($dp['recommended_depth']) {
        $specs['Recommended Depth'] = $dp['recommended_depth'];
    }
    if ($dp['ideal_power']) {
        $specs['Ideal Power'] = $dp['ideal_power'];
    }
    if ($dp['performance_curves']) {
        $curves = json_decode($dp['performance_curves'], true);
        if ($curves) {
            $specs['Performance Curves'] = json_encode($curves);
        }
    }
    $specs['Category'] = $doyinCatName;
    $specs['Brand'] = 'Doyin Kenya';
    if ($dp['in_stock'] == 0) {
        $specs['Availability'] = 'Out of Stock';
    }

    // Build description
    $description = buildDescription($dp['name'], $doyinCatName, $specs);

    // Stock
    $stock = $dp['in_stock'] ? rand(5, 20) : 0;

    // Image - use placeholder since Doyin products don't have images
    $image = null;

    try {
        Product::create([
            'category_id' => $solarshopCat->id,
            'brand_id' => $doyinBrand->id,
            'name' => $dp['name'],
            'slug' => $slug,
            'sku' => $sku,
            'short_description' => "Doyin Kenya {$doyinCatName}: {$dp['name']}",
            'description' => $description,
            'regular_price' => $price,
            'sale_price' => $salePrice,
            'stock' => $stock,
            'image' => $image,
            'gallery' => null,
            'specifications' => $specs ?: null,
            'views' => rand(10, 500),
            'is_featured' => false,
            'is_active' => true,
            'meta_title' => Str::limit($dp['name'].' - Doyin Kenya Solar Water Pump', 70),
            'meta_description' => Str::limit("Buy {$dp['name']} from Doyin Kenya - Quality solar water pumps with genuine warranty. Nationwide delivery in Kenya.", 160),
        ]);

        $imported++;
        echo "✓ Imported: {$dp['name']} (KSh ".number_format($price, 0).")\n";
    } catch (Exception $e) {
        $skipped++;
        echo "✗ Failed: {$dp['name']} - ".$e->getMessage()."\n";
    }
}

echo "\n=== Import Complete ===\n";
echo "Imported: $imported\n";
echo "Skipped: $skipped\n";

function estimatePrice(string $name, int $categoryId): float
{
    $name = strtolower($name);

    // Extract power in watts/HP
    $watts = 0;
    if (preg_match('/(\d+(?:\.\d+)?)\s*kw/i', $name, $m)) {
        $watts = (float) $m[1] * 1000;
    } elseif (preg_match('/(\d+(?:\.\d+)?)\s*w\b/i', $name, $m)) {
        $watts = (float) $m[1];
    } elseif (preg_match('/\((\d+(?:\.\d+)?)\s*hp\)/i', $name, $m)) {
        $watts = (float) $m[1] * 746;
    }

    // Base prices by category (KSh)
    $basePrices = [
        1 => 45000,  // DC PUMP ordinary impeller
        2 => 55000,  // DC PUMP stainless steel impeller
        3 => 65000,  // AC/DC hybrid PUMP
        4 => 35000,  // AC PUMP ordinary impeller
        5 => 25000,  // AC booster PUMP
        6 => 30000,  // AC well PUMP
        7 => 40000,  // Sewage PUMP
        8 => 5000,   // Accessories
        9 => 15000,  // AC PUMP Motor
        10 => 12000, // AC Controller
        11 => 8000,  // Pump Body
    ];

    $base = $basePrices[$categoryId] ?? 25000;

    // Scale by power (roughly KSh 50-100 per watt for solar pumps)
    if ($watts > 0) {
        $price = max($base, $watts * 80);
    } else {
        $price = $base;
    }

    // Add some variation
    return round($price * (0.9 + mt_rand(0, 20) / 100), -2); // Round to nearest 100
}

function buildDescription(string $name, string $category, array $specs): string
{
    $html = "<p><strong>{$name}</strong> from <strong>Doyin Kenya</strong> — a reliable {$category} for solar water pumping applications. Available at competitive prices with genuine warranty and nationwide delivery in Kenya.</p>";

    if (! empty($specs)) {
        $html .= '<p>Key Specifications:</p><table class="spec-table"><tbody>';
        foreach ($specs as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $html .= '<tr><td><strong>'.e($key).'</strong></td><td>'.e($value).'</td></tr>';
            }
        }
        $html .= '</tbody></table>';
    }

    $html .= '<p>Contact us for expert advice on sizing, installation services, and bulk pricing for your solar water pumping project.</p>';

    return $html;
}
