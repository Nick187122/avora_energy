<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$brand = App\Models\Brand::where('slug', 'doyin-kenya')->first();

if ($brand) {
    $products = App\Models\Product::where('brand_id', $brand->id)->get(['name', 'regular_price', 'sale_price', 'id']);
    echo "Found {$products->count()} products for brand: {$brand->name}\n";
    foreach ($products as $p) {
        echo "  - ID: {$p->id} | {$p->name} | Regular: {$p->regular_price} | Sale: {$p->sale_price}\n";
    }
} else {
    echo "Brand not found\n";
}