<?php

use App\Models\Category;
use Illuminate\Contracts\Console\Kernel;

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$pumpCat = Category::where('slug', 'solar-water-pumps')->first();
echo 'Parent: '.$pumpCat->id.': '.$pumpCat->name.PHP_EOL;

$children = Category::where('parent_id', $pumpCat->id)->get();
foreach ($children as $c) {
    echo $c->id.': '.$c->name.' | '.$c->slug.PHP_EOL;
}
