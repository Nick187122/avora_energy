<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$products = App\Models\Product::where('name', 'like', '%doyin%')
    ->orWhere('name', 'like', '%Doyin%')
    ->get(['name', 'regular_price', 'sale_price', 'brand_id']);

echo "Found {$products->count()} Doyin products\n";
foreach ($products as $p) {
    echo "  - {$p->name} | Regular: {$p->regular_price} | Sale: {$p->sale_price} | Brand: " . ($p->brand ? $p->brand->name : 'no brand') . "\n";
}