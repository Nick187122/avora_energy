<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Console\Kernel;

echo "=== Checking Products After Seeder ===\n\n";

$total = Product::count();
echo "Total products: $total\n";

$doyinProducts = Product::whereHas('brand', fn ($q) => $q->where('slug', 'doyin-kenya'))->count();
echo "Doyin Kenya products: $doyinProducts\n";

$categories = Category::where('parent_id', 48)->get();
echo "\nCategories under Solar Water Pumps:\n";
foreach ($categories as $c) {
    $count = Product::where('category_id', $c->id)->count();
    echo "  {$c->name} ({$c->slug}): $count products\n";
}
