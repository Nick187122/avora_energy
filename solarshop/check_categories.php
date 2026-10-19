<?php

use App\Models\Category;
use Illuminate\Contracts\Console\Kernel;

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$categories = Category::where('slug', 'like', '%pump%')->get();
foreach ($categories as $c) {
    echo $c->id.': '.$c->name.' | '.$c->slug.PHP_EOL;
}

echo "--- All top-level categories ---\n";
$topCats = Category::whereNull('parent_id')->orderBy('sort_order')->get();
foreach ($topCats as $c) {
    echo $c->id.': '.$c->name.' | '.$c->slug.PHP_EOL;
}
