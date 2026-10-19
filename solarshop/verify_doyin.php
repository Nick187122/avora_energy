<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Contracts\Console\Kernel;

echo "=== Sample Doyin Products ===\n\n";

$products = Product::whereHas('brand', fn ($q) => $q->where('slug', 'doyin-kenya'))
    ->with('category', 'brand')
    ->limit(10)
    ->get();

foreach ($products as $p) {
    echo "ID: {$p->id}\n";
    echo "  Name: {$p->name}\n";
    echo "  Slug: {$p->slug}\n";
    echo "  SKU: {$p->sku}\n";
    echo "  Category: {$p->category->name} ({$p->category->slug})\n";
    echo "  Brand: {$p->brand->name}\n";
    echo '  Regular Price: KSh '.number_format($p->regular_price, 2)."\n";
    echo '  Sale Price: KSh '.number_format($p->sale_price ?? 0, 2)."\n";
    echo "  Stock: {$p->stock}\n";
    echo '  Featured: '.($p->is_featured ? 'Yes' : 'No')."\n";
    echo '  Active: '.($p->is_active ? 'Yes' : 'No')."\n";
    echo '  Specs: '.json_encode($p->specifications)."\n";
    echo "\n";
}
