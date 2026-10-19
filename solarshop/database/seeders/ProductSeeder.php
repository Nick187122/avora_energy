<?php

namespace Database\Seeders;

use App\Services\CatalogImporter;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        try {
            app(CatalogImporter::class)->run();

            $this->command?->info('Product catalog imported.');
        } catch (\RuntimeException $e) {
            $this->command?->error($e->getMessage());
        }
    }
}
