<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$brand = App\Models\Brand::where('slug', 'doyin-kenya')->first();

if ($brand) {
    $products = App\Models\Product::where('brand_id', $brand->id)->get();
    echo "Updating {$products->count()} Doyin Kenya products...\n";
    
    foreach ($products as $p) {
        $p->regular_price = 0;
        $p->sale_price = null;
        $p->save();
        echo "  - Updated: {$p->name}\n";
    }
    
    echo "Done! All Doyin Kenya product pricing removed.\n";
} else {
    echo "Brand not found\n";
}