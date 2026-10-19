<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$brands = App\Models\Brand::where('name', 'like', '%doyin%')
    ->orWhere('name', 'like', '%Doyin%')
    ->get(['name', 'slug']);

echo "Found {$brands->count()} Doyin brands\n";
foreach ($brands as $b) {
    echo "  - {$b->name} | Slug: {$b->slug}\n";
}